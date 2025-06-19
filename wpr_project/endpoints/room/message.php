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

Returned data:
- {"message": <packed message data>, "user": <packed user data>}
*/

http_response_code(500);

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

$message = Message::create($room->get_game(), $_POST["message"], $user);
$result = $message->save();

if (!$result) {
    http_response_code(500);
    return;
}

$room->send_message_events($message);

// Return the message back to the user.
// This is because they could have modified the message after pressing "Send" but before confirming the message has been sent.
$result = ["message" => $message->pack(), "user" => $message->get_user()->pack()];

echo json_encode($result);

http_response_code(200);