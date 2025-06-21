<?php
/*
/endpoints/room/get_data.php

Retrieves data for the currently ongoing game in the room the user is in.

No POST parameters, session must be active!

Status codes:
- 200 - retrieval successful
- 404 - user not in room

Returned data:
- {"game": <packed game data>}
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
$result["game"] = $room->get_game()->pack();
echo json_encode($result);

http_response_code(200);