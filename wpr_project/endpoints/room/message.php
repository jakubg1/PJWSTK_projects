<?php
/*
/endpoints/room/message.php

Sends a message to the chat, on the game assigned to the current session.

POST parameters:
- message - message to be sent

Status codes:
- 200 - message sent successfully
- 400 - no message specified
- 401 - user is not logged in
- 404 - user is not in any room
*/

include "../../functions.php";

session_start();
$user = get_user();

if (empty($_POST["message"])) {
    http_response_code(400);
    return;
}

if (!is_user_logged_in()) {
    http_response_code(403);
    return;
}

$room = Room::get($_SESSION["room_id"]);
if (!$room) {
    http_response_code(404);
    return;
}

$message = Message::create($room->get_game(), $user, $_POST["message"]);
$message->save();