<?php
/*
/endpoints/room/leave.php

Leaves a currently joined room.

No POST parameters, session must be active!

Status codes:
- 200 - leave successful
- 404 - user was not in any room
*/

http_response_code(500);

include "../../functions.php";

session_start();
$user = get_user();

$room = Room::get($_SESSION["room_id"]);
if (!$room) {
    http_response_code(404);
    return;
}

$room->remove_player($user);
$room->save();

unset($_SESSION["room_id"]);

// Send a notification to everyone in the room that we've left.
$message = Message::create($room->get_game(), $user->get_name() . " opuścił grę.");
$message->save();

$room->send_message_events($message);

http_response_code(200);