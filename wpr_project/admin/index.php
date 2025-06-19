<?php
include "../functions.php";
session_start();
ensure_admin();
html_start();

html_title("Panel administratora");
echo "Tu nic jeszcze nie ma!<br/>";
echo "<a href='../index.php'>Strona główna</a><br/>";

html_end();
?>