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
            $players = $room->get_players();
            echo $room->get_name() . " (" . count($players) . "/" . $room->get_max_players() . ") (";
            for ($i = 0; $i < sizeof($players); $i++) {
                if ($i > 0)
                    echo ", ";
                echo $players[$i]->get_name();
            }
            echo ") o:" . $room->get_owner()->get_name() . " <a href='join.php?id=" . $room->get_id() . "'>Dołącz</a><br/>";
        }
    } else {
        echo "W chwili obecnej nie ma żadnych pokoi. Załóż nowy pokój!<br/>";
    }
    echo "</div>";
    echo "<a href='../index.php'>Strona główna</a><br/>";
}

html_end();