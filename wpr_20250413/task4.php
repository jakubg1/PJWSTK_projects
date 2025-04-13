<!--
Code written as part of PJWSTK lessons
Task 4: Display a different site for certain IP addresses
-->

<h1>Różne strony</h1>

<?php
if (!$fd = fopen("ip.txt", "r")) {
    echo "Nie udało się otworzyć pliku ip.txt!<br>";
    return;
}
$ips = array();
while (!feof($fd)) {
    $ips[] = fgets($fd);
}
fclose($fd);
$ip = $_SERVER['REMOTE_ADDR'];
echo "Twój adres IP to $ip<br>";
if (in_array($ip, $ips)) {
    include("task4_yes.php");
} else {
    include("task4_no.php");
}
echo "Koniec<br>";
?>