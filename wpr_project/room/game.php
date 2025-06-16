<?php
include "../functions.php";

html_start();
session_start();

echo "Jesteś w pokoju.<br/>";

echo "ID: " . $_SESSION["room_id"] . "<br/>";

echo "<a href='../index.php'>Strona główna</a>";

html_end();