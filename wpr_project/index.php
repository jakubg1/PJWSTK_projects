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
if (isset($_SESSION["user_id"])) {
    $user = get_user();
    if (!$user) {
        echo "BŁĄD! Jesteś zalogowany jako nieistniejący użytkownik! Następuje reset sesji.<br/>";
        session_destroy();
        session_start();
    } else {
        echo "Witaj, <b>" . $user->get_name() . "</b>!<br/>";
        echo "Ostatnio byłeś aktywny: <b>" . $user->get_last_active_at() . "</b><br/>";
        echo "<a id='logout' href='user/logout.php'>Wyloguj się</a><br/>";
        $user->set_last_active_at();
        $user->save();
    }
} else {
    echo "Jeżeli jeszcze nie masz konta, zarejestruj się aby móc rozmawiać na czacie i śledzić swoje statystyki!<br/>";
    echo "Możesz też grać jako gość.<br/>";
    echo "<a href='user/login.php'>Zaloguj się</a><br/>";
    echo "<a href='user/register.php'>Rejestracja</a><br/>";
    echo "<a href='test.php'>Test</a><br/>";
}

echo "Wybierz grę:<br/>";
echo "<a href='room/list.php?game=checkers'>Warcaby</a><br/>";
echo "<a href='room/list.php?game=uno'>UNO</a><br/>";

html_end();
?>