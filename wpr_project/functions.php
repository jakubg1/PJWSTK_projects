<?php
// Used if the site is not located at the root of the page.
$FS_PREFIX = "/git/PJWSTK_projects/wpr_project";

// Include everything here. This way we only need to include functions.php on any other page!
include "database.php";
include "objects/game.php";
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

// Places a <h1> header.
function html_title($title) {
    echo "<h1>" . $title . "</h1>";
}

// Places an invisible status box, which will show up when the JavaScript's `status()` function is called.
function html_status_box() {
    echo "<div id='status' hidden='true'></div>";
}

// Creates an HTML form.
// Fields have the following values:
// - id - Variable name which will be a POST variable when the form is submitted.
// - type - Input type, any HTML <input type="X"> works here. By default, this field is hidden.
// - label - Input description, without the colon. Optional.
// - value - Default value for the input. Optional.
// - required - Whether the value must be provided in the form. Optional.
function html_form($id, $action, $fields) {
    echo "<form id='" . $id . "' action='" . $action . "' method='POST'>";
    foreach ($fields as $field) {
        if (isset($field["label"])) {
            echo "<label for='" . $field["id"] . "'>" . $field["label"] . ":" . (isset($field["required"]) ? " *" : "") . "</label>";
        }
        $type = isset($field["type"]) ? $field["type"] : "hidden";
        $input = "<input type='" . $type . "'";
        if (isset($field["id"])) {
            $input .= " id='" . $field["id"] . "' name='" . $field["id"] . "'";
        }
        if (isset($field["value"])) {
            $input .= " value='". $field["value"] . "'";
        }
        if (isset($field["required"])) {
            $input .= " required='true'";
        }
        $input .= ">";
        echo $input;
    }
    echo "</form>";
}

// Returns the current user stored in the session.
function get_user() {
    if (!isset($_SESSION["user_id"])) {
        return null;
    }
    return User::get($_SESSION["user_id"]);
}

function is_user_logged_in() {
    $user = get_user();
    return $user != null && $user->get_type() != "guest";
}

function is_user_admin() {
    $user = get_user();
    return $user != null && $user->get_type() == "admin";
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

function is_game_type_supported($game_type) {
    return $game_type == "checkers" || $game_type == "uno";
}

function translate_game_type($game_type) {
    switch ($game_type) {
        case "checkers":
            return "Warcaby";
        case "uno":
            return "UNO";
    }
}