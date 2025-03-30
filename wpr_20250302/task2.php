<!--
Code written as part of PJWSTK lessons
Task 2: Prime numbers
-->

<?php
echo "<h1>Zadanie 2</h1>";
function print_primes($n) {
    for ($i = 2; $i <= $n; $i++) {
        $prime = true;
        for ($j = 2; $j <= floor(sqrt($i)); $j++) {
            if ($i % $j == 0) {
                $prime = false;
                break;
            }
        }
        if ($prime) {
            echo $i . "<br>";
        }
    }
}
print_primes(100);
?>