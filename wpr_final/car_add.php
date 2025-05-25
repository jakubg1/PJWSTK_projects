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

<h1>Dodaj samochód</h1>

<form action="car_add.php" method="POST">
    <label for="marka">Marka: </label>
    <input type="text" name="marka">
    <br/>
    <label for="model">Model: </label>
    <input type="text" name="model">
    <br/>
    <label for="rok">Rok: </label>
    <input type="number" name="rok">
    <br/>
    <label for="cena">Cena: </label>
    <input type="number" name="cena">
    <br/>
    <label for="opis">Opis: </label>
    <textarea name="opis"></textarea>
    <br/>
    <input type="submit" value="Wyślij">
</form>

<?php
// czy wysylamy teraz samochod?
if (!isset($_POST["marka"])) {
    return;
}

// zbieranie parametrow
$marka = $_POST["marka"];
$model = $_POST["model"];
$cena = $_POST["cena"];
$rok = $_POST["rok"];
$opis = $_POST["opis"];

// polaczenie z baza danych
$db = mysqli_connect("localhost", "root", "", "mojaBaza");
if (!$db) {
    echo "Błąd połączenia z bazą danych... :(";
    return;
}

// wyslanie parametrow do bazy
$query = "INSERT INTO samochody VALUES (null, \"$marka\", \"$model\", $cena, $rok, \"$opis\");";
$result = mysqli_query($db, $query);
if (!$result) {
    echo "Błąd wykonania zapytania... :(";
    return;
}

// sukces!
echo "Samochód dodany pomyślnie!!!";
?>