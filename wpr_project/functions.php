<?php
// Used if the site is not located at the root of the page.
$FS_PREFIX = "/git/PJWSTK_projects/wpr_project";

// Include everything here. This way we only need to include functions.php on any other page!
include "database.php";
include "objects/Game.php";
include "objects/GameCheckers.php";
include "objects/GameCheckersPawn.php";
include "objects/Message.php";
include "objects/QueuedEvent.php";
include "objects/Room.php";
include "objects/User.php";
include "tick.php";

function html_start($title = "Stuff is cooking here", $uncentered = false) {
    global $FS_PREFIX;
    echo "<!DOCTYPE html>";
    echo "<html>";
    echo "<head>";
    echo "<title>" . $title . "</title>";
    echo "<meta charset='UTF-8'>";
    echo "<link rel='stylesheet' href='" . $FS_PREFIX . "/style.css'>";
    echo "<script src='" . $FS_PREFIX . "/jquery.js'></script>";
    echo "<script src='" . $FS_PREFIX . "/functions.js'></script>";
    echo "</head>";
    echo "<body>";
    html_topbar();
    echo "<div id='main'>";
    if (!$uncentered) {
        echo "<div id='content'>";
    }
}

function html_end($uncentered = false) {
    if (!$uncentered) {
        echo "</div>";
    }
    echo "</div>";
    echo "</body>";
    echo "</html>";
}

// Places a top bar which shows who are we logged on as.
function html_topbar() {
    global $FS_PREFIX;
    $user = get_user();
    $room = get_room();
    echo "<div id='topbar'>";
    if ($user->get_type() != "guest") {
        echo "Zalogowany jako: " . $user->get_name();
        if (!$room) {
            echo " | <a href='" . $FS_PREFIX . "/user/logout.php'>Wyloguj się</a>";
            if (is_user_admin()) {
                echo " | <a href='" . $FS_PREFIX . "/admin'>Panel administratora</a>";
            }
        }
    } else {
        echo "Nie jesteś zalogowany (" . $user->get_name() . ")";
        if (!$room) {
            echo " | <a href='" . $FS_PREFIX . "/user/login.php'>Zaloguj się</a>";
            echo " | <a href='" . $FS_PREFIX . "/user/register.php'>Zarejestruj się</a>";
        }
    }
    echo "</div>";
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
// - max_length - Maximum input length, optional.
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
        if (isset($field["max_length"])) {
            $input .= " maxlength='" . $field["max_length"] . "'";
        }
        $input .= " autocomplete='off'>";
        echo $input;
    }
    echo "</form>";
}

// Returns the current time as a timestamp ready to save in the database.
function get_timestamp() {
    return date("Y-m-d H:i:s");
}

// Returns the current user stored in the session.
function get_user() {
    if (isset($_SESSION["user_id"])) {
        $user = User::get($_SESSION["user_id"]);
        // There is a chance the user we were logged in as doesn't exist anymore.
        if ($user)
            return $user;
    }
    // No user ID. Check the cookie for a guest account.
    $user = User::get_from_cookie();
    if ($user)
        return $user;
    // No stored user. Generate a new one and store it in a cookie.
    $user = User::create_guest();
    $user->save_cookie();
    return $user;
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

// Returns the current room stored in this session.
function get_room() {
    if (!isset($_SESSION["room_id"])) {
        return null;
    }
    return Room::get($_SESSION["room_id"]);
}

$GAME_TYPES = [
    "checkers" => ["name" => "Warcaby", "max_players" => 2],
    "uno" => ["name" => "UNO", "max_players" => 4]
];

function is_game_type_supported($game_type) {
    global $GAME_TYPES;
    return isset($GAME_TYPES[$game_type]);
}

function translate_game_type($game_type) {
    global $GAME_TYPES;
    return $GAME_TYPES[$game_type]["name"];
}

function get_max_players($game_type) {
    global $GAME_TYPES;
    return $GAME_TYPES[$game_type]["max_players"];
}