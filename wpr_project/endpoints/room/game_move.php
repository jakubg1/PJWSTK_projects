<?php
/*
/endpoints/room/game_move.php

Makes a move (Checkers).

POST parameters:
- sx, sy - source pawn position
- x, y - target pawn position

Status codes:
- 200 - move successful
- 400 - invalid move
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

$x = $_POST["x"] ?? null;
$y = $_POST["y"] ?? null;
$sx = $_POST["sx"] ?? null;
$sy = $_POST["sy"] ?? null;

if ($x == null || $y == null || $sx == null || $sy == null) {
    http_response_code(400);
    return;
}

// Check if the position is in bounds.
if ($x < 0 || $x > 7 || $y < 0 || $y > 7 || $sx < 0 || $sx > 7 || $sy < 0 || $sy > 7) {
    http_response_code(400);
    return;
}

// Set a game state.
$game = $room->get_game();
$game->set_state("last_x", $x);
$game->set_state("last_y", $y);
$game->set_state("last_sx", $sx);
$game->set_state("last_sy", $sy);
var_dump($game->pack());
$game->save();

// For now, send a message.
$message = Message::create($room->get_game(), "move: " . $x . " " . $y . " " . $sx . " " . $sy);
$message->save();

$room->send_message_events($message);

http_response_code(200);