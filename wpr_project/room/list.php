<?php
include "../functions.php";
session_start();
html_start();

$game = "";
if (isset($_GET["game"])) {
    $game = $_GET["game"];
}

if (!is_game_type_supported($game)) {
    echo "Błąd - nieprawidłowa gra!<br/>";
    $game = "";
}

if ($game != "") {
    echo "Wybrana gra: " . translate_game_type($game) . "<br/>";
    if (is_user_logged_in()) {
        echo "<a href='create.php?game=" . $game . "'>Załóż nowy pokój</a><br/>";
    } else {
        echo "<a href='../user/login.php'>Zaloguj się, aby utworzyć pokój!</a><br/>";
    }
    echo "Lista pokoi:<br/>";
    echo "<div id='rooms'>";
    $rooms = Room::get_list_by_game_type($game);
    if (sizeof($rooms) > 0) {
        foreach ($rooms as $room) {
            echo "id: " . $room->get_id() . ", game_id: " . $room->get_game()->get_id() . ", name: " . $room->get_name() . "<br/>";
        }
    } else {
        echo "W chwili obecnej nie ma żadnych pokoi. Załóż nowy pokój!<br/>";
    }
    echo "</div>";
    echo "<a href='../index.php'>Strona główna</a><br/>";
}

html_end();