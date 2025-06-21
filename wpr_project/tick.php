<?php
// Because this file is included in almost every endpoint/page through `functions.php`, we can use this to our advantage.
// Bootstrap some code to run updates/checks periodically.
$path = $_SERVER["DOCUMENT_ROOT"] . "/git/PJWSTK_projects/wpr_project/last_tick.txt";
$contents = file_exists($path) ? file_get_contents($path) : null;
if (!$contents || intval($contents) < time()) {
    file_put_contents($path, strval(time()));
    tick();
}

// Ran periodically, once per second (or rarer if traffic on the page is lower).
// Performs routine checks and actions, such as:
// - Deletes guest accounts which have not seen any activity for more than 24 hours.
// - Checks if any player in the game has timed out.
function tick() {
    // Detect player timeouts.
    $rooms = Room::get_list();
    foreach ($rooms as $room) {
        $count = $room->get_player_count();
        $room->remove_dead_players();
        // Save the room only if we did kick someone out.
        if ($room->get_player_count() != $count)
            $room->save();
    }
}