<?php
include "../functions.php";

html_start();
session_start();

echo "Jesteś w pokoju.<br/>";
$room = Room::get($_SESSION["room_id"]);
echo "ID: " . $room->get_id() . "<br/>";
echo "Nazwa: " . $room->get_name() . "<br/>";
echo "<a id='leave' href='list.php?game=" . $room->get_game()->get_game_type() . "'>Opuść pokój</a>";
echo "<br/>";
echo "<div id='rooms'>";
echo "<div id='chat_messages'>";
echo "</div>";
echo "<form id='chat' action='/endpoints/room/message.php' method='POST'>";
echo "<label for='message'>Wiadomość:</label>";
echo "<input type='text' id='message' name='message' required='true'>";
echo "<input type='submit' value='Wyślij'>";
echo "</form>";
echo "</div>";

html_end();
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

    function heartbeat() {
        // Send a heartbeat and throw the player away if something is wrong.
        ajax(
            "/endpoints/room/heartbeat.php",
            null,
            function(response) {
                //console.log(response);
            },
            function(response) {
                redirect("/room/list.php?disconnected=1");
            }
        );
        // Retrieve any messages.
        ajax(
            "/endpoints/room/get_events.php",
            null,
            function(response) {
                try {
                    data = JSON.parse(response);
                } catch (e) {
                    ;
                }
                for (let i = 0; i < data.length; i++) {
                    handleEvent(data[i]);
                }
            },
            null
        )
    }

    function handleEvent(event) {
        if (event.type == "message") {
            chatMessage(event.message.message);
        }
    }

    // Handle chat messages.
    function chatMessage(message) {
        $("#chat_messages").append("<div class='message'>");
        $("#chat_messages .message").last().text(message);
    }

    // Handle the chat form.
    registerForm(
        "chat",
        function(formData) {
            return true;
        },
        function(response) {
            let chatbox = $("#chat input#message");
            chatMessage(chatbox.val());
            chatbox.val("");
        },
        function(response) {
            let errors = {
                403: "Nie jesteś zalogowany. Zaloguj się i spróbuj ponownie."
            };
            status(xhrError(response, errors));
        }
    );
</script>