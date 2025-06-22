<?php
include "../functions.php";
session_start();
html_start("Gra", true);

// game_wrapper
// - game_box
// - game_under
//   - chat
//   - player_list

echo "<div id='game_wrapper'>";
echo "<div id='game_box'>";
$room = Room::get($_SESSION["room_id"]);
if ($room) {
    $game = $room->get_game()->get_game_type();
    if ($game == "checkers") {
        echo "<iframe id='game' src='../games/checkers/index.html'></iframe>";
    } elseif ($game == "uno") {
        echo "Uno jeszcze nie wspierane, ale witamy!";
    }
} else {
    echo "Nie jesteś w pokoju!";
}
echo "</div>";
echo "<div id='game_under'>";
echo "<div id='chat'>";
echo "<div id='header'>Czat</div>";
echo "<div id='chat_messages'></div>";
echo "<form id='chat_form' action='/endpoints/room/message.php' method='POST'>";
echo "<label for='message'>Wiadomość:</label>";
echo "<input type='text' id='message' name='message' required='true' maxlength='255' autocomplete='off'>";
echo "<input type='submit' value='Wyślij'>";
echo "</form>";
echo "</div>";
echo "<div id='player_list'>";
echo "<div id='header'>Lista graczy</div>";
echo "<div id='players'></div>";
echo "<div class='pane'>";
echo "<input id='btn_leave' class='red' type='submit' value='Opuść pokój'>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "</div>";

html_end(true);
?>

<script>
    let game = null;
    try {
        game = $("#game")[0].contentWindow;
    } catch (e) {
        // Game failed to load.
    }

    // TODO: Maybe a better way to store/fetch the game type?
    <?php
        if ($room) {
            echo "let gameType = '" . $room->get_game()->get_game_type() . "';";
        } else {
            echo "let gameType = null;";
        }
    ?>

    let userData = null;
    // Returns our user data.
    function fetchUserData() {
        ajax(
            "/endpoints/user/get_data.php",
            null,
            function(response) {
                let data = tryJson(response);
                userData = data.user;
            },
            function(response) {
                chatMessage("Nie udało się pobrać danych gracza!");
            }
        );
    }
    fetchUserData();
    
    // Handle chat messages.
    let chat = $("#chat_messages");
    let chatbox = $("#chat_form input#message");

    function chatMessage(message, sender) {
        chat.append("<div class='message'>");
        let msgbox = chat.find(".message").last();
        if (sender != null) {
            msgbox.text("<" + sender + "> " + message);
        } else {
            msgbox.addClass("system");
            msgbox.text(message);
        }
        chat.scrollTop(chat[0].scrollHeight);
    }

    let playerList = $("#players");
    
    // Handle the player list.
    function addPlayer(player, isOwner, debugInfo) {
        playerList.append("<div class='player'>");
        let playerBox = playerList.find(".player").last();
        playerBox.append("<div class='profile_pic'>");
        playerBox.append("<div class='name'>");
        playerBox.append("<div class='stats'>");
        playerBox.append("<div class='role'>");
        playerBox.append("<div class='debug'>");
        playerBox.append("<div class='actions'>");
        playerBox.find(".profile_pic").append("<img src='../profile_pictures/_default.png'>");
        playerBox.find(".name").text(player.name + (isOwner ? " (host)" : ""));
        if (userData != null && userData.id == player.id)
            playerBox.find(".name").addClass("you");
        playerBox.find(".stats").text("W:3 / P:2 / R:0");
        playerBox.find(".role").text("(czarny)");
        playerBox.find(".debug").text(debugInfo);
        let actions = playerBox.find(".actions");
        actions.append("<input id='btn_leave' class='small red' type='submit' value='Wyrzuć'>");
        actions.append("<input id='btn_leave' class='small red' type='submit' value='Promuj'>");
        actions.append("<input id='btn_leave' class='small red' type='submit' value='Wycisz'>");
        actions.append("<input id='btn_leave' class='small red' type='submit' value='Zbanuj'>");
    }

    function clearPlayerList() {
        playerList.empty();
    }

    // Handle the chat form.
    registerForm(
        "chat_form",
        function(formData) {
            chatbox.val("");
            return true;
        },
        function(response) {
            let data = tryJson(response);
            chatMessage(data.message.message, data.user.name);
        },
        function(response) {
            let errors = {
                403: "Nie jesteś zalogowany. Zaloguj się i spróbuj ponownie."
            };
            status(xhrError(response, errors));
        }
    );

    // Handle the "Leave room" button.
    registerButton(
        "btn_leave",
        "/endpoints/room/leave.php",
        null,
        function(response) {
            redirect("/room/list.php?game=" + gameType);
        },
        function(response) {
            redirect("/index.php");
        }
    );

    // Send a heartbeat every second so that the server does not kick us out.
    setInterval(heartbeat, 1000);
    heartbeat(); // First heartbeat

    function heartbeat() {
        // Send a heartbeat and throw the player away if something is wrong.
        ajax(
            "/endpoints/room/heartbeat.php",
            null,
            function(response) {
                //console.log(response);
            },
            function(response) {
                //redirect("/room/list.php?game=" + gameType + "&disconnected=1");
                chatMessage("System wyrzucił cię z pokoju. Prawdopodobnie przez buga. Przeszukaj logi. Normalnie system wyrzuciłby cię do listy pokoi! Kliknij \"Opuść pokój\" i dołącz lub stwórz pokój ponownie.");
            }
        );
        // Retrieve any messages.
        ajax(
            "/endpoints/room/get_events.php",
            null,
            function(response) {
                let data = tryJson(response);
                for (let i = 0; i < data.length; i++) {
                    handleEvent(data[i]);
                }
            }
        );
        // Update the player list.
        ajax(
            "/endpoints/room/get_data.php",
            null,
            function(response) {
                let data = tryJson(response);
                clearPlayerList();
                for (let i = 0; i < data.players.length; i++) {
                    let player = data.players[i];
                    addPlayer(player, data.room.owner == player.id, "ts: " + data.room.players[player.id].last_heartbeat_at);
                }
            }
        );
    }

    // Handle events coming from the heartbeat.
    function handleEvent(event) {
        if (event.type == "message") {
            if (event.user) {
                chatMessage(event.message.message, event.user.name);
            } else {
                chatMessage(event.message.message);
            }
        } else if (event.type == "move") {
            game.postMessage({"type": "move", "move": event.move});
        } else {
            console.log(event);
        }
    }

    // Handle the game.
    // Initialize the game when the page has been just loaded.
    ajax(
        "/endpoints/room/get_game.php",
        null,
        function(response) {
            // Send pawns to the board.
            let data = tryJson(response);
            game.postMessage({"type": "setPawns", "pawns": data.game.states.pawns});
            game.postMessage({"type": "setTurn", "turn": data.game.states.turn == 1 ? "white" : "black"});
        }
    );

    // Receive input from the board and forward it to the server.
    window.addEventListener("message",
        function(e) {
            if (e.data.type == "move") {
                let move = e.data.move;
                let canContinue = e.data.canContinue;
                ajax(
                    "/endpoints/room/game_move.php",
                    {x: move.x, y: move.y, sx: move.sx, sy: move.sy, kx: move.kx, ky: move.ky, continue: canContinue},
                    function(response) {
                        console.log(response);
                    },
                    function(response) {
                        chatMessage("Podczas wykonywania ruchu nastąpił nieoczekiwany błąd.");
                    }
                );
            }
        }
    );
</script>