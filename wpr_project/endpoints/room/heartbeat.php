<?php
/*
/endpoints/room/heartbeat.php

Renews the heartbeat timer. This is used so that we can track whether the user is online.

No POST parameters, session must be active!

Status codes:
- 200 - heartbeat successful
- 404 - user is not in any room
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

$room->update_player_heartbeat($user);
$room->save();

http_response_code(200);