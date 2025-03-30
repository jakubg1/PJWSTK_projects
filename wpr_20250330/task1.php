<h1>Data urodzenia</h1>
<form action="task1.php" method="get">
    <label for="date">Podaj swoją datę urodzenia: </label>
    <input type="date" name="date">
    <input type="submit" value="Wyślij">
</form>

<?php
if (empty($_GET["date"])) {
    return;
}
setlocale(LC_ALL, 'pl_PL');
$birthday_date_str = $_GET["date"];
$birthday_date = strtotime($birthday_date_str);
$current_date = time();
$current_date_t = getdate($current_date);
$birthday_date_t = getdate($birthday_date);
$next_birthday_date = mktime(0, 0, 0, $birthday_date_t['mon'], $birthday_date_t['mday'], $current_date_t['year']);
$age = $current_date_t["year"] - $birthday_date_t["year"] - 1;
if ($next_birthday_date <= $current_date) {
    $next_birthday_date = mktime(0, 0, 0, $birthday_date_t['mon'], $birthday_date_t['mday'], $current_date_t['year'] + 1);
    $age++;
}
$days_before_next_birthday = ceil(($next_birthday_date - $current_date) / 86400);
$weekdays = array("niedzielę", "poniedziałek", "wtorek", "środę", "czwartek", "piątek", "sobotę");
echo "Urodziłeś się w " . $weekdays[$birthday_date_t["wday"]] . "<br/>";
echo "Masz " . $age . " lat<br/>";
echo "Kolejne urodziny będziesz miał za " . $days_before_next_birthday . " dni<br/>";
?>