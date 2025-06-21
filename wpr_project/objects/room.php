<?php
class Room {
    private $id;
    private $name;
    private $owner;
    private $game_id;
    private $password;
    private $players;

    public function get_id() {
        return $this->id;
    }

    public function set_id($id) {
        $this->id = $id;
    }

    public function get_name() {
        return $this->name;
    }

    public function set_name($name) {
        $this->name = $name;
    }

    public function get_owner() {
        return User::get($this->owner);
    }

    public function set_owner($owner) {
        $this->owner = $owner->get_id();
    }

    public function get_game() {
        return Game::get($this->game_id);
    }

    public function set_game($game) {
        $this->game_id = $game->get_id();
    }

    public function has_password() {
        return $this->password != null;
    }

    public function check_password($password) {
        if ($this->password == null)
            return true;
        return password_verify($password, $this->password);
    }

    public function set_password($password) {
        if (!$password) {
            $this->password = null;
        }
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    // Returns a list of Users for each user which is in the room.
    public function get_players() {
        $players = [];
        foreach ($this->players as $id => $data) {
            $players[] = User::get($id);
        }
        return $players;
    }

    // Returns data associated with the provided player in this room.
    public function get_player_data($player) {
        return $this->players[$player->get_id()] ?? null;
    }

    // Adds the provided player to the room.
    public function add_player($player) {
        $this->players[$player->get_id()] = ["last_heartbeat_at" => get_timestamp()];
    }

    // Removes the provided player from the room.
    public function remove_player($player) {
        $this->remove_player_by_id($player->get_id());
    }

    // Removes the provided player from the room by their ID.
    public function remove_player_by_id($player_id) {
        unset($this->players[$player_id]);
        if ($this->get_player_count() == 0) {
            return;
        }
        // If the provided player is the room's owner, a new owner is randomly assigned.
        if ($this->owner == $player_id) {
            foreach ($this->players as $id => $data) {
                $this->owner = $id;
                break;
            }
        }
    }

    // Removes players who haven't sent the heartbeat for 15 seconds.
    public function remove_dead_players() {
        foreach ($this->players as $id => $data) {
            if (time() > strtotime($data["last_heartbeat_at"]) + 15) {
                $this->remove_player_by_id($id);
                // Send a notification to everyone in the room that this player has timed out.
                $user = User::get($id);
                $message = Message::create($this->get_game(), $user->get_name() . " opuścił grę (timeout).");
                $message->save();
                $this->send_message_events($message);
            }
        }
    }

    // Updates the heartbeat timestamp for the provided player.
    public function update_player_heartbeat($player) {
        $this->players[$player->get_id()]["last_heartbeat_at"] = get_timestamp();
    }

    // Returns the number of players in the room.
    public function get_player_count() {
        return count($this->players);
    }

    // Returns the maximum number of players in the room.
    public function get_max_players() {
        return get_max_players($this->get_game()->get_game_type());
    }

    // Returns whether the room is full.
    public function is_full() {
        return $this->get_player_count() >= $this->get_max_players();
    }

    // Sends an event to every player in this room, optionally except the sender if provided.
    public function send_event($type, $payload, $sender) {
        foreach ($this->get_players() as $player) {
            if (!$sender || $player->get_id() != $sender->get_id()) {
                $event = QueuedEvent::create($player, $type);
                $event->set_payload($payload);
                $event->save();
            }
        }
    }

    // Sends a message event to every other player in the room.
    public function send_message_events($message) {
        $this->send_event("message", ["id" => $message->get_id()], $message->get_user());
    }

    // Creates a new room.
    // The room automatically starts with its owner added as a player!
    public static function create($name, $owner) {
        $room = new Room();
        $room->id = null;
        $room->name = $name;
        $room->set_owner($owner);
        $room->players = [];
        $room->add_player($owner);
        return $room;
    }

    // Retrieves a room by ID
    public static function get($id) {
        $row = db_select_one("SELECT * FROM rooms WHERE id = ?", [$id]);
        if (!$row) {
            return null;
        }
        $row["players"] = [];
        $player_rows = db_select("SELECT * FROM room_players WHERE room_id = ?", [$id]);
        foreach ($player_rows as $player_row) {
            $row["players"][$player_row["user_id"]] = ["last_heartbeat_at" => $player_row["last_heartbeat_at"]];
        }
        return Room::load($row);
    }

    // Retrieves a list of all rooms.
    public static function get_list() {
        $rows = db_select("SELECT rooms.* FROM rooms JOIN games ON rooms.game_id = games.id");
        $rooms = [];
        foreach ($rows as $row) {
            $rooms[] = Room::get($row["id"]);
        }
        return $rooms;
    }

    // Retrieves a list of rooms by game type ("checkers" or "uno")
    public static function get_list_by_game_type($game_type) {
        $rows = db_select("SELECT rooms.* FROM rooms JOIN games ON rooms.game_id = games.id WHERE games.game_type = ?", [$game_type]);
        $rooms = [];
        foreach ($rows as $row) {
            $rooms[] = Room::get($row["id"]);
        }
        return $rooms;
    }

    // Saves the room to database, or deletes it, if it's empty.
    public function save() {
        if ($this->get_player_count() > 0) {
            $arrays = [
                "players" => ["table" => "room_players", "field" => "room_id", "subfield" => "user_id", "subfields" => ["last_heartbeat_at"]]
            ];
            return db_save_object($this, "rooms", ["id", "name", "owner", "game_id", "password"], $arrays);
        } else {
            $this->delete();
        }
    }

    // Removes the room from database, as well as any relevant player entries.
    public function delete() {
        db_remove("rooms", ["id" => $this->id]);
        db_remove("room_players", ["room_id" => $this->id]);
        $this->id = null;
    }

    // Loads the room from given database row
    private static function load($row) {
        if (!$row) {
            return null;
        }
        $room = new Room();
        $room->id = $row["id"];
        $room->name = $row["name"];
        $room->owner = $row["owner"];
        $room->game_id = $row["game_id"];
        $room->password = $row["password"];
        $room->players = $row["players"];
        return $room;
    }

    // Packs the room data for ease of use in database functions
    public function pack() {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "owner" => $this->owner,
            "game_id" => $this->game_id,
            "password" => $this->password,
            "players" => $this->players
        ];
    }
}