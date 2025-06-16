<?php
// Used if the site is not located at the root of the page.
$FS_PREFIX = "/git/PJWSTK_projects/wpr_project";

// Include everything here. This way we only need to include functions.php on any other page!
include "database.php";
include "objects/room.php";
include "objects/user.php";

function html_start($title = "Stuff is cooking here") {
    global $FS_PREFIX;
    echo "<html>";
    echo "<head>";
    echo "<title>" . $title . "</title>";
    echo "<meta charset='UTF-8'>";
    echo "<link rel='stylesheet' href='" . $FS_PREFIX . "/style.css'>";
    echo "<script src='" . $FS_PREFIX . "/jquery.js'></script>";
    echo "<script src='" . $FS_PREFIX . "/functions.js'></script>";
    echo "</head>";
    echo "<body>";
    echo "<div id='main'>";
}

function html_end() {
    echo "</div>";
    echo "</body>";
    echo "</html>";
}

function is_user_logged_in() {
    if (!isset($_SESSION["user_id"])) {
        return false;
    }
    $user = User::get($_SESSION["user_id"]);
    return $user->get_type() != "guest";
}

function is_user_admin() {
    if (!isset($_SESSION["user_id"])) {
        return false;
    }
    $user = User::get($_SESSION["user_id"]);
    return $user->get_type() == "admin";
}

// Makes sure the user is logged in or throws 403 otherwise.
function ensure_login() {
    if (!is_user_logged_in()) {
        http_response_code(403);
        exit;
    }
}

// Makes sure the user is an admin or throws 403 otherwise.
function ensure_admin() {
    if (!is_user_admin()) {
        http_response_code(403);
        exit;
    }
}