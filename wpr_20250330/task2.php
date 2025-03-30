<h1>Silnia i czas wykonywania</h1>
<form action="task2.php" method="get">
    <label for="n">Podaj liczbę: </label>
    <input type="number" name="n">
    <input type="submit" value="Oblicz">
</form>

<?php
function factorial_rec($n) {
    if ($n == 1) {
        return 1;
    }
    return $n * factorial_rec($n - 1);
}

function factorial_loop($n) {
    $value = 1;
    for ($i = 1; $i <= $n; $i++) {
        $value *= $i;
    }
    return $value;
}

if (empty($_GET["n"])) {
    return;
}
$n = $_GET["n"];
$t1 = microtime(true);
$result = factorial_rec($n);
$t1 = microtime(true) - $t1;
echo "<h2>Rekursja</h2>";
echo "Wynik: <b>" . $result . "</b><br/>";
echo "Czas wykonywania: <b>" . round($t1 * 1000, 1) . "ms</b><br/>";
$t2 = microtime(true);
$result = factorial_loop($n);
$t2 = microtime(true) - $t2;
echo "<h2>Pętla</h2>";
echo "Wynik: <b>" . $result . "</b><br/>";
echo "Czas wykonywania: <b>" . round($t2 * 1000, 1) . "ms</b><br/>";
echo "<h2>Wniosek</h2>";
if ($t1 < $t2) {
    echo "Szybciej działała <b>rekursja</b> ";
    echo "o <b>" . round(($t2 - $t1) * 1000, 1) . "ms</b><br/>";
} else {
    echo "Szybciej działała <b>pętla</b> ";
    echo "o <b>" . round(($t1 - $t2) * 1000, 1) . "ms</b><br/>";
}
?>