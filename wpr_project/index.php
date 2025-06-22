<?php
// WORK IN PROGRESS!!!
// DO NOT RATE until this notice is removed!

include "functions.php";
session_start();
html_start();

if (isset($_GET["logout"])) {
    echo "<div class='success'>Wylogowano pomyślnie!</div>";
}

echo "Witaj na portalu z dwoma grami!<br/>";
$user = get_user();
if ($user->get_type() != "guest") {
    echo "Witaj, <b>" . $user->get_name() . "</b>!<br/>";
    echo "Ostatnio byłeś aktywny: <b>" . $user->get_last_active_at() . "</b><br/>";
    $user->set_last_active_at();
    $user->save();
} else {
    echo "Uwaga! Grasz jako gość.<br/>";
    echo "Jeżeli jeszcze nie masz konta, zarejestruj się aby móc rozmawiać na czacie i śledzić swoje statystyki!<br/>";
}

html_title("Wybierz grę:");
echo "<div id='games'>";
echo "<a href='room/list.php?game=checkers'><div class='game pane'><img src='games/checkers/icon.png'>Warcaby</div></a>";
echo "<a href='room/list.php?game=uno'><div class='game pane'>UNO - nie działa :(</div></a>";
echo "</div>";

html_end();
?>