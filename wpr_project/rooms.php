<?php
include "functions.php";
session_start();
html_start();

$game = "";
if (isset($_GET["game"])) {
    $game = $_GET["game"];
}

if ($game == "checkers") {
    echo "Wybrana gra: Warcaby<br/>";
} elseif ($game == "uno") {
    echo "Wybrana gra: UNO<br/>";
} else {
    echo "Błąd - nieprawidłowa gra!<br/>";
    $game = "";
}

if ($game != "") {
    echo "Lista pokoi:<br/>";
    echo "<div id='rooms'>";
    $rooms = db_get_room_list($game);
    echo "</div>";
}

html_end();