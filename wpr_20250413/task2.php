<!--
Code written as part of PJWSTK lessons
Task 2: Site visit counter
-->

<h1>Licznik odwiedzin</h1>

<?php
$count = 0;
if ($fd = fopen("licznik.txt", "r")) {
    $count = intval(fgets($fd));
    fclose($fd);
}
$count++;
echo "Tą stronę odwiedzono $count razy<br>";
if ($fd = fopen("licznik.txt", "w")) {
    fwrite($fd, $count);
    fclose($fd);
} else {
    echo "Nie zapisano pliku licznik.txt!<br>";
}
?>