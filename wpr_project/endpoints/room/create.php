<?php
/*
/endpoints/room/create.php

Creates a Room and a Game with it.
The game is assigned to the room.
The room ID is saved in the session.

POST parameters:
- name - room name
- game_type - game type
- password - optional room password

Status codes:
- 200 - room creation successful
- 400 - incorrect data
- 403 - user not logged in
*/

include "../../functions.php";

session_start();

if (!is_user_logged_in()) {
    http_response_code(403);
    return;
}

if (empty($_POST["name"]) || !is_game_type_supported($_POST["game_type"])) {
    http_response_code(400);
    return;
}

$room = Room::create($_POST["name"]);
if (!empty($_POST["password"])) {
    $room->set_password($_POST["password"]);
}
$game = Game::create($_POST["game_type"]);
$game->save();
$room->set_game($game);
$room->save();

$_SESSION["room_id"] = $room->get_id();