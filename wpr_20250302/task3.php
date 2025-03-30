<!--
Code written as part of PJWSTK lessons
Task 3: Fibonacci numbers
-->

<?php
echo "<h1>Zadanie 3</h1>";
function print_fibonacci($n) {
    $numbers = array(1, 1);
    for ($i = 2; $i < $n; $i++) {
        $numbers[] = $numbers[$i - 1] + $numbers[$i - 2];
    }
    $k = 1;
    for ($i = 0; $i < sizeof($numbers) - 1; $i += 2) {
        echo $k . " " . $numbers[$i] . "<br>";
        $k++;
    }
    echo $n . ". liczba Fibonacciego: " . $numbers[sizeof($numbers) - 1];
}
print_fibonacci(30);
?>