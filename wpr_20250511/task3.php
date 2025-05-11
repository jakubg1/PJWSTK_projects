<!--
Code written as part of PJWSTK lessons
Task 3: Site visit counter but use cookies instead (and don't include refreshes)
-->

<h1>Licznik odwiedzin</h1>

<?php
$count = 0;
if ($fd = fopen("licznik.txt", "r")) {
    $count = intval(fgets($fd));
    fclose($fd);
}
if (!isset($_COOKIE["visited"])) {
    $count++;
}
setcookie("visited", 1, time() + 30, "/");
echo "Tą stronę odwiedzono $count razy<br>";
if ($count > 20) {
    echo "<br/>";
    echo "Gratulacje!!! Odwiedziłeś tą stronę ponad 20 razy!";
}
if ($fd = fopen("licznik.txt", "w")) {
    fwrite($fd, $count);
    fclose($fd);
} else {
    echo "Nie zapisano pliku licznik.txt!<br>";
}
?>