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

http_response_code(500);

include "../../functions.php";

session_start();
$user = get_user();

if (!is_user_logged_in()) {
    http_response_code(403);
    return;
}

if (empty($_POST["name"]) || !is_game_type_supported($_POST["game_type"])) {
    http_response_code(400);
    return;
}

// Leave the old room if we were in any.
if ($_SESSION["room_id"]) {
    $old_room = Room::get($_SESSION["room_id"]);
    if ($old_room) {
        $old_room->remove_player($user);
        $old_room->save();
    }
}

// Create a new room with a game alongside it.
$room = Room::create($_POST["name"], $user);
if (!empty($_POST["password"])) {
    $room->set_password($_POST["password"]);
}
$game = Game::create($_POST["game_type"]);
$game->save();
$room->set_game($game);
$room->save();

$_SESSION["room_id"] = $room->get_id();

// Send a notification to everyone in the room that we've... created the room? (For consistency)
$message = Message::create($room->get_game(), $user->get_name() . " założył pokój.");
$message->save();

$room->send_message_events($message);

// For now, set up the game here.
//$game->set_state("pawns", " b b b bb b b b  b b b b                w w w w  w w w ww w w w ");

http_response_code(200);