<!--
Code written as part of PJWSTK lessons
Task 3: Link generator
-->

<h1>Lista linków</h1>

<?php
if (!$fd = fopen("linki.txt", "r")) {
    echo("Nie udało się otworzyć pliku linki.txt!<br>");
    return;
}
echo "<table>";
echo "<tr><th>Link</th><th>Opis</th></tr>";
while (!feof($fd)) {
    $line = fgets($fd);
    $data = explode(";", $line);
    echo "<tr>";
    echo "<td><a href=\"".$data[0]."\">".$data[0]."</a></td>";
    echo "<td>".$data[1]."</td>";
}
echo "</table>";
fclose($fd);
?>