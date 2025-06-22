<?php
/*
/endpoints/room/join.php

Joins a room. If the player was already in a different room, they are removed from that room.

POST parameters:
- id - room ID
- password - optional room password

Status codes:
- 200 - join successful
- 400 - incorrect data
- 401 - incorrect password
- 403 - room already full
- 404 - room not found
*/

http_response_code(500);

include "../../functions.php";

session_start();
$user = get_user();

if (empty($_POST["id"])) {
    http_response_code(400);
    return;
}

$room = Room::get($_POST["id"]);
if (!$room) {
    http_response_code(404);
    return;
}

$password = $_POST["password"] ?? "";
if (!$room->check_password($password)) {
    http_response_code(401);
    return;
}

if ($room->is_full()) {
    http_response_code(403);
    return;
}

// Leave the old room if we were in any.
if (isset($_SESSION["room_id"])) {
    $old_room = Room::get($_SESSION["room_id"]);
    if ($old_room && $old_room != $room) {
        $old_room->remove_player($user);
        $old_room->save();
    }
}

$room->add_player($user);
$room->save();

// Send a notification to everyone in the room that we've joined.
$message = Message::create($room->get_game(), $user->get_name() . " dołączył do gry.");
$message->save();

$room->send_message_events($message);

$_SESSION["room_id"] = $room->get_id();

http_response_code(200);