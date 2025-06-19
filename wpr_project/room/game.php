<?php
include "../functions.php";
session_start();
html_start("Gra", true);

// game_wrapper
// - game
// - game_under
//   - chat
//   - player_list

echo "<div id='game_wrapper'>";
echo "<div id='game'>";
$room = Room::get($_SESSION["room_id"]);
if ($room) {
    echo "Jesteś w pokoju.<br/>";
    echo "ID: " . $room->get_id() . "<br/>";
    echo "Nazwa: " . $room->get_name() . "<br/>";
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
echo "<input type='text' id='message' name='message' required='true' maxlength='255'>";
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
    // TODO: Maybe a better way to store/fetch the game type?
    let gameType = "<?php echo $room->get_game()->get_game_type(); ?>";

    // Whenever we leave this page, the server should know that we left the room.
    $("#leave").on("click", function() {
        ajax("/endpoints/room/leave.php", null, null, null, false);
    });

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
                redirect("/room/list.php?game=" + gameType + "&disconnected=1");
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
                    addPlayer(data.players[i].name);
                }
            }
        );
    }

    function handleEvent(event) {
        if (event.type == "message") {
            if (event.user) {
                chatMessage(event.message.message, event.user.name);
            } else {
                chatMessage(event.message.message);
            }
        }
    }

    let chat = $("#chat_messages");
    let chatbox = $("#chat_form input#message");

    // Handle chat messages.
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
    function addPlayer(name) {
        playerList.append("<div class='player'>");
        let playerbox = playerList.find(".player").last();
        playerbox.text(name);
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
        }
    );
</script>