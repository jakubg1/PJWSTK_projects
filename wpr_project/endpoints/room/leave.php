<?php
/*
/endpoints/room/leave.php

Leaves a currently joined room.

No POST parameters, session must be active!

Status codes:
- 200 - leave successful
- 404 - user was not in any room
*/

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