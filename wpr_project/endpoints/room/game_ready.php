<?php
/*
/endpoints/room/game_start.php

Marks this user as ready to start the game (or not).

POST parameters:
- cancel - if set, marks the player as not ready

Status codes:
- 200 - request successful
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

$cancel = ($_POST["continue"] ?? false) == "true";

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