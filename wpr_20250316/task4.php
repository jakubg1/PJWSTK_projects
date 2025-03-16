<html>
<body>
    <h1>Czy liczba jest liczbą pierwszą?</h1>
    <form action="task4.php" method="get">
        <input type="number" name="num">
        <input type="submit" value="Sprawdź">
    </form>
</body>
</html>
<?php
if (!isset($_GET["num"])) {
    return;
}
$num = $_GET["num"];
if (!is_numeric($num)) {
    echo "Nie podałeś liczby";
    return;
}
if ($num <= 0) {
    echo "Podana liczba nie jest dodatnia";
    return;
}
if ($num % 1 != 0) {
    echo "Podana liczba nie jest całkowita";
    return;
}
if ($num == 1) {
    echo "1 nie jest liczbą pierwszą!";
    return;
}
if ($num == 2 || $num == 3) {
    echo "2 (lub 3) są liczbami pierwszymi!";
    return;
}
// lucky number: 389348923789237
$iter = floor(sqrt($num));
for ($i = 2; $i <= $iter; $i++) {
    if ($num % $i == 0) {
        echo "<b>Podana liczba nie jest pierwsza :(</b>";
        echo "<br>";
        echo "Najmniejsza liczba która ją dzieli to <b>" . $i . "</b>";
        echo "<br>";
        echo "Wykonano <b>" . ($i - 1) . "</b> z <b>" . ($iter - 1) . "</b> przebiegów pętli";
        return;
    }
}
echo "<b>Podana liczba jest pierwsza! :D</b>";
echo "<br>";
echo "Wykonano <b>" . ($iter - 1) . "</b> przebiegów pętli";
?>