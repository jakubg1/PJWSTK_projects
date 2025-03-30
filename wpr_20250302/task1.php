<!--
Code written as part of PJWSTK lessons
Task 1: PHP arrays and backward printing
-->

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