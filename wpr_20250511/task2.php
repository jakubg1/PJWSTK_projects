<!--
Code written as part of PJWSTK lessons
Task 2: Site visit counter but use cookies instead
-->

<h1>Licznik odwiedzin</h1>

<?php
$count = 0;
if (isset($_COOKIE["count"])) {
    $count = $_COOKIE["count"];
}
$count++;
echo "Tą stronę odwiedzono $count razy<br>";
if ($count > 20) {
    echo "<br/>";
    echo "Gratulacje!!! Odwiedziłeś tą stronę ponad 20 razy!";
}
setcookie("count", $count, time() + (86400 * 30), "/");
?>