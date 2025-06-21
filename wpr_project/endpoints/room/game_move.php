<?php
/*
/endpoints/room/game_move.php

Makes a move (Checkers).

POST parameters:
- sx, sy - source pawn position
- x, y - target pawn position
- kx, ky - killed pawn position (this should be determined by the server!)
- continue - whether the player can continue (this should be determined by the server!)
If the player can continue, the turn will not change.
The moved pawn will also not be promoted to a queen if it's at its final row.

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

// If this is not a Checkers game, back out.
$game = $room->get_game();
if (!$game instanceof GameCheckers) {
    http_response_code(400);
    return;
}

$x = $_POST["x"] ?? null;
$y = $_POST["y"] ?? null;
$sx = $_POST["sx"] ?? null;
$sy = $_POST["sy"] ?? null;
$kx = $_POST["kx"] ?? null;
$ky = $_POST["ky"] ?? null;
$continue = ($_POST["continue"] ?? false) == "true";

if ($x == null || $y == null || $sx == null || $sy == null || ($kx == null ^ $ky == null)) {
    http_response_code(400);
    return;
}

// Check if the position is in bounds.
if ($x < 0 || $x > 7 || $y < 0 || $y > 7 || $sx < 0 || $sx > 7 || $sy < 0 || $sy > 7) {
    http_response_code(400);
    return;
}
// Check kill position, if it exists, too.
if ($kx != null && ($kx < 0 || $kx > 7) || $ky != null && ($ky < 0 || $ky > 7)) {
    http_response_code(400);
    return;
}

// Update the game state.
$game->move_pawn($sx, $sy, $x, $y);
if ($kx != null && $ky != null)
    $game->set_pawn($kx, $ky);
if (!$continue) {
    $game->try_promote_pawn($x, $y);
    $game->next_turn();
}
$game->debug_print_state();
$game->save();

// Broadcast the move to other players in the room.
$room->send_event("move", ["x" => $x, "y" => $y, "sx" => $sx, "sy" => $sy, "kx" => $kx, "ky" => $ky, "continue" => $continue], $user);

http_response_code(200);