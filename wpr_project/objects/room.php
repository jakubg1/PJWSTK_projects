<?php
class Room {
    private $id;
    private $name;
    private $game_id;
    private $password;

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

    public function get_game() {
        return Game::get($this->game_id);
    }

    public function set_game($game) {
        $this->game_id = $game->get_id();
    }

    public function check_password($password) {
        return password_verify($password, $this->password);
    }

    public function set_password($password) {
        if (!$password) {
            $this->password = null;
        }
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    // Loads the room from given database row
    private static function load($row) {
        if (!$row) {
            return null;
        }
        $room = new Room();
        $room->id = $row["id"];
        $room->name = $row["name"];
        $room->game_id = $row["game_id"];
        $room->password = $row["password"];
        return $room;
    }

    // Packs the room data for ease of use in database functions
    private function pack() {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "game_id" => $this->game_id,
            "password" => $this->password
        ];
    }

    // Creates a new room
    public static function create($name) {
        $room = new Room();
        $room->id = null;
        $room->name = $name;
        return $room;
    }

    // Retrieves a room by ID
    public static function get($id) {
        $row = db_select_one("SELECT * FROM rooms WHERE id = ?", [$id]);
        return Room::load($row);
    }

    // Retrieves a list of rooms by game type ("checkers" or "uno")
    public static function get_list_by_game_type($game_type) {
        $rows = db_select("SELECT * FROM rooms JOIN games ON rooms.game_id = games.id WHERE games.game = ?", [$game_type]);
        $rooms = [];
        foreach ($rows as $row) {
            $rooms[] = Room::load($row);
        }
        return $rooms;
    }

    // Saves the room to database
    public function save() {
        $data = $this->pack();
        if (isset($this->id)) {
            // Room exists, because it has an ID assigned.
            return db_update("UPDATE rooms SET id = :id, name = :name, game_id = :game_id, password = :password WHERE id = :id", $data);
        } else {
            // Room does not exist, because it has no ID assigned.
            // Avoid an error by removing unnecessary fields.
            unset($data["id"]);
            $result = db_insert("INSERT INTO rooms VALUES (NULL, :name, :game_id, :password)", $data);
            if (!$result)
                return false;
            // Populate the ID field.
            $this->id = $result;
            return true;
        }
    }
}