<!--
Code written as part of PJWSTK lessons
Task 1: Load a file and print lines, reversed
-->

<h1>Wylistuj plik w odwróconej kolejności linii</h1>
<form action="task1.php" method="get">
    <label for="file">Podaj plik: </label>
    <input type="file" name="file">
    <input type="submit" value="Wyślij">
</form>

<?php
if (empty($_GET["file"])) {
    return;
}
$file = $_GET["file"];
if (!$fd = fopen($file, "r")) {
    echo "Nie udało się otworzyć pliku $file!<br>";
    return;
}
$lines = array();
while (!feof($fd)) {
    $line = fgets($fd);
    $lines[] = $line;
}
fclose($fd);
for ($i = count($lines) - 1; $i >= 0; $i--) {
    echo $lines[$i] . "<br>";
}
?>