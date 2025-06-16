<?php
include "../functions.php";
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
    if (is_user_logged_in()) {
        echo "<a href='create.php'>Załóż nowy pokój</a><br/>";
    } else {
        echo "<a href='../user/login.php'>Zaloguj się, aby tworzyć pokoje!</a><br/>";
    }
    echo "Lista pokoi:<br/>";
    echo "<div id='rooms'>";
    $rooms = Room::get_list_by_game_type($game);
    if (sizeof($rooms) > 0) {
        foreach ($rooms as $room) {
            echo "id: " . $room->get_id() . ", name: " . $room->get_name() . "<br/>";
        }
    } else {
        echo "W chwili obecnej nie ma żadnych pokoi. Załóż nowy pokój!<br/>";
    }
    echo "</div>";
}

html_end();