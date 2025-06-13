<?php
// Include everything here. This way we only need to include functions.php on any other page!
include_once "database.php";
include_once "objects/user.php";

function html_start($title = "Stuff is cooking here") {
    echo "<html>";
    echo "<head>";
    echo "<title>" . $title . "</title>";
    echo "<meta charset='UTF-8'>";
    echo "<link rel='stylesheet' href='style.css'>";
    echo "<script src='jquery.js'></script>";
    echo "<script src='functions.js'></script>";
    echo "</head>";
    echo "<body>";
    echo "<div id='main'>";
}

function html_end() {
    echo "</div>";
    echo "</body>";
    echo "</html>";
}