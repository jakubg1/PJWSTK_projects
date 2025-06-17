<?php
class Game {
    private $id;
    private $game_type;
    private $started_at;
    private $finished_at;

    public function get_id() {
        return $this->id;
    }

    public function set_id($id) {
        $this->id = $id;
    }

    public function get_game_type() {
        return $this->game_type;
    }

    public function set_game_type($game_type) {
        $this->game_type = $game_type;
    }

    public function get_started_at() {
        return $this->started_at;
    }

    public function set_started_at() {
        $this->started_at = get_timestamp();
    }

    public function get_finished_at() {
        return $this->finished_at;
    }

    public function set_finished_at() {
        $this->finished_at = get_timestamp();
    }

    // Loads the game from given database row
    private static function load($row) {
        if (!$row) {
            return null;
        }
        $game = new Game();
        $game->id = $row["id"];
        $game->game_type = $row["game_type"];
        $game->started_at = $row["started_at"];
        $game->finished_at = $row["finished_at"];
        return $game;
    }

    // Packs the game data for ease of use in database functions
    public function pack() {
        return [
            "id" => $this->id,
            "game_type" => $this->game_type,
            "started_at" => $this->started_at,
            "finished_at" => $this->finished_at
        ];
    }

    // Creates a new game
    public static function create($game_type) {
        $game = new Game();
        $game->id = null;
        $game->game_type = $game_type;
        return $game;
    }

    // Retrieves a game by ID
    public static function get($id) {
        $row = db_select_one("SELECT * FROM games WHERE id = ?", [$id]);
        return Game::load($row);
    }

    // Saves the game to database
    public function save() {
        return db_save_object($this, "games", ["id", "game_type", "started_at", "finished_at"]);
    }
}