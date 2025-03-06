<?php
echo "<h1>Zadanie 1</h1>";
$fruit[] = "jablko";
$fruit[] = "banan";
$fruit[] = "pomarancza";
foreach ($fruit as $f) {
    $letters = str_split($f);
    $word = "";
    foreach ($letters as $letter) {
        $word = $letter . $word;
    }
    echo $word . "<br>";
}
?>