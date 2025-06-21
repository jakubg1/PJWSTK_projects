<?php
/*
/endpoints/room/get_events.php

Retrieves queued events for the player.

No POST parameters, session must be active!

Status codes:
- 200 - retrieval successful
- 403 - user not logged in

Returned data:
- list of events, where each event is:
    - {"type": "message", "message": <packed message data>, ["user": <packed user data>]}
        - `user` field does not exist if it's a system message.
    - {"type": "move", "move": {"x": x, "y": y, "sx": sx, "sy": sy, "continue": continue, ["kx": kx, "ky": ky]}}
        - `x`, `y` - target pawn position
        - `sx`, `sy` - source pawn position
        - `kx`, `ky` - killed pawn position (optional)
        - `continue` - whether same player will continue with another move
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
    $type = $event->get_type();
    if ($type == "message") {
        $payload = $event->get_payload();
        $message = Message::get($payload["id"]);
        $subresult = ["type" => "message", "message" => $message->pack()];
        $sender = $message->get_user();
        if ($sender)
            $subresult["user"] = $sender->pack();
        $result[] = $subresult;
    } elseif ($type == "move") {
        $result[] = ["type" => "move", "move" => $event->get_payload()];
    }
    $event->delete();
}

echo json_encode($result);

http_response_code(200);