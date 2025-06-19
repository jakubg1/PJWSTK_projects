<?php
/*
/endpoints/room/get_data.php

Retrieves data for the current room the user is in, and the player list.

No POST parameters, session must be active!

Status codes:
- 200 - retrieval successful
- 404 - user not in room

Returned data:
- {"room": <packed room data>, "players": [<packed player data>, ...]}
*/

http_response_code(500);

include "../../functions.php";

session_start();

$room = Room::get($_SESSION["room_id"]);
if (!$room) {
    http_response_code(404);
    return;
}

$result = [];

// Fetch the room data.
$result["room"] = $room->pack();
$result["players"] = [];
foreach ($room->get_players() as $player) {
    $result["players"][] = $player->pack();
}

echo json_encode($result);

http_response_code(200);