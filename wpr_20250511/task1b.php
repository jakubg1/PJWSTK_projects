<!--
Code written as part of PJWSTK lessons
Task 1: Saving data in a session
-->

<h1>Pobieranie danych i zapisywanie w sesji</h1>

<?php
if (!isset($_GET['n'])) {
    echo "Nie podałeś liczby osób";
    return;
}
$n = $_GET['n'];
if ($n < 1) {
    echo "Błędna liczba osób";
    return;
}
if ($n > 100) {
    echo "Podałeś za dużo osób! Maksymalnie możesz wpisać 100.";
    return;
}

session_start();
if (isset($_GET["imie"]) && isset($_GET["nazwisko"])) {
    $_SESSION['imie'] = $_GET["imie"];
    $_SESSION['nazwisko'] = $_GET["nazwisko"];
    echo "Dane zostały zapisane!";
}

function print_inputs($n) {
    $imie = "";
    $nazwisko = "";
    if (isset($_GET['imie'][$n])) {
        $imie = $_GET['imie'][$n];
    }
    if (isset($_GET['nazwisko'][$n])) {
        $nazwisko = $_GET['nazwisko'][$n];
    }
    echo "<h2>Osoba ".($n+1)."</h2>";
    echo "<label for='imie[$n]'>Imię: </label>";
    echo "<input type='text' name='imie[$n]' value='$imie'>";
    echo "<br/>";
    echo "<label for='nazwisko[$n]'>Nazwisko: </label>";
    echo "<input type='text' name='nazwisko[$n]' value='$nazwisko'>";
}

echo "<form action='task1b.php' method='get'>";
echo "<input type='hidden' name='n' value='$n'>";
for ($i = 0; $i < $n; $i++) {
    print_inputs($i);
}
echo "<br/>";
echo "<br/>";
echo "<input type='submit' value='Zapisz dane w sesji'>";
echo "</form>";
echo "<form action='task1c.php' method='get'>";
echo "<input type='submit' value='Przejdź do podsumowania'>";
echo "</form>";
?>
