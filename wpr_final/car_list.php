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

<h1>Lista samochodów</h1>

<?php
// polaczenie z baza danych
$db = mysqli_connect("localhost", "root", "", "mojaBaza");
if (!$db) {
    echo "Błąd połączenia z bazą danych... :(";
    return;
}

// pobieranie samochodow z bazy
$query = "SELECT * FROM samochody ORDER BY rok DESC;";
$result = mysqli_query($db, $query);
if (!$result) {
    echo "Błąd wykonania zapytania... :(";
    return;
}

// wyswietlanie w tabeli
echo "<table>";
echo "<tr><th>ID</th><th>Marka</th><th>Model</th><th>Cena</th><th>Akcje</th></tr>";
while ($row = mysqli_fetch_array($result)) {
    echo "<tr>";
    echo "<td>${row["id"]}</td>";
    echo "<td>${row["marka"]}</td>";
    echo "<td>${row["model"]}</td>";
    echo "<td>${row["cena"]}</td>";
    echo "<td><a href=\"car_details.php?id=${row["id"]}\">Szczegóły</a></td>";
    echo "</tr>";
}
echo "</table>";
?>