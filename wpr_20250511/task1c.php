<!--
Code written as part of PJWSTK lessons
Task 1: Saving data in a session
-->

<h1>Pobieranie danych i zapisywanie w sesji</h1>

<?php
session_start();
if (!isset($_SESSION["imie"]) || !isset($_SESSION["nazwisko"])) {
    echo "Nic nie było w sesji. ";
    echo "Najpewniej odświeżyłeś stronę lub nie zapisałeś danych z formularza.";
    return;
}
$imie = $_SESSION["imie"];
$nazwisko = $_SESSION["nazwisko"];
echo "<h2>Podsumowanie</h2>";
for ($i = 0; $i < sizeof($imie); $i++) {
    echo "Osoba ".($i+1).": ".$imie[$i]." ".$nazwisko[$i]."<br>";
}
session_destroy();
?>