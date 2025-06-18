<?php
/*
/endpoints/room/get_events.php

Retrieves queued events for the player.

No POST parameters, session must be active!

Status codes:
- 200 - retrieval successful
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

$result = [];

// Fetch the messages.
$events = QueuedEvent::get_list_by_user($user);
foreach ($events as $event) {
    if ($event->get_type() == "message") {
        $payload = $event->get_payload();
        $message = Message::get($payload["id"]);
        $result[] = ["type" => "message", "message" => $message->pack()];
    }
    $event->delete();
}

echo json_encode($result);

http_response_code(200);