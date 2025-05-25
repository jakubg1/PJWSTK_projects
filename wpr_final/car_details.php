<!--
Code written as part of PJWSTK lessons
Final Task: Car portal
-->

<head>
    <link rel="stylesheet" href="style.css">
</head>

<div id="banner">
    <a href="index.php">Strona główna</a>
    <a href="car_list.php">Lista samochodów</a>
    <a href="car_add.php">Dodaj samochód</a>
</div>

<h1>Szczegóły pojazdu</h1>

<?php
// walidacja
if (!isset($_GET["id"])) {
    echo "Nie podano parametru!";
    return;
}

// zbieranie parametrow
$id = $_GET["id"];

// polaczenie z baza danych
$db = mysqli_connect("localhost", "root", "", "mojaBaza");
if (!$db) {
    echo "Błąd połączenia z bazą danych... :(";
    return;
}

// pobieranie samochodu z bazy
$query = "SELECT * FROM samochody WHERE id = $id;";
$result = mysqli_query($db, $query);
if (!$result) {
    echo "Błąd wykonania zapytania... :(";
    return;
}
$row = mysqli_fetch_array($result);
if (!$row) {
    echo "Nie znaleziono samochodu... :(";
    return;
}

// wyswietlanie
echo "ID: <b>${row["id"]}</b><br/>";
echo "Marka: <b>${row["marka"]}</b><br/>";
echo "Model: <b>${row["model"]}</b><br/>";
echo "Cena: <b>${row["cena"]}</b><br/>";
echo "Rok: <b>${row["rok"]}</b><br/>";
echo "Opis: <b>${row["opis"]}</b><br/>";
?>