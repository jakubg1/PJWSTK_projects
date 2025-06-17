<?php
include "../functions.php";

html_start();
session_start();

echo "Jesteś w pokoju.<br/>";
$room = Room::get($_SESSION["room_id"]);
echo "ID: " . $room->get_id() . "<br/>";
echo "Nazwa: " . $room->get_name() . "<br/>";
echo "<a id='leave' href='list.php?game=" . $room->get_game()->get_game_type() . "'>Opuść pokój</a>";

html_end();
?>

<script>
    // Whenever we leave this page, the server should know that we left the room.
    $("#leave").on("click", function() {
        ajax("/endpoints/room/leave.php", null, null, null, false);
    });
</script>