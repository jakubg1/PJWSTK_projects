<?php
class Game {
    private $id;
    private $game_type;
    private $started_at;
    private $finished_at;
    private $states;

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

    public function get_state($state) {
        return $this->states[$state];
    }

    public function set_state($state, $value) {
        $this->states[$state] = $value;
    }

    public function setup() {
        // Empty. Overridable by subclasses for initial game state setup!
    }

    // Creates a new game
    public static function create($game_type) {
        if ($game_type == "checkers")
            $game = new GameCheckers();
        else
            $game = new Game();
        $game->id = null;
        $game->game_type = $game_type;
        $game->state = [];
        return $game;
    }

    // Retrieves a game by ID.
    // If this is a Checkers game, returns an instance of GameCheckers instead.
    public static function get($id) {
        $row = db_select_one("SELECT * FROM games WHERE id = ?", [$id]);
        if (!$row) {
            return null;
        }
        $row["states"] = [];
        $states_rows = db_select("SELECT * FROM game_states WHERE game_id = ?", [$id]);
        foreach ($states_rows as $states_row) {
            $row["states"][$states_row["state_id"]] = $states_row["value"];
        }
        return Game::load($row);
    }

    // Saves the game to database
    public function save() {
        $arrays = [
            "states" => ["table" => "game_states", "field" => "game_id", "subfield" => "state_id", "subfields" => "value"]
        ];
        return db_save_object($this, "games", ["id", "game_type", "started_at", "finished_at"], $arrays);
    }

    // Loads the game from given database row
    // If this is a Checkers game, returns an instance of GameCheckers instead.
    private static function load($row) {
        if (!$row) {
            return null;
        }
        if ($row["game_type"] == "checkers")
            $game = new GameCheckers();
        else
            $game = new Game();
        $game->id = $row["id"];
        $game->game_type = $row["game_type"];
        $game->started_at = $row["started_at"];
        $game->finished_at = $row["finished_at"];
        $game->states = $row["states"];
        return $game;
    }

    // Packs the game data for ease of use in database functions
    public function pack() {
        return [
            "id" => $this->id,
            "game_type" => $this->game_type,
            "started_at" => $this->started_at,
            "finished_at" => $this->finished_at,
            "states" => $this->states
        ];
    }
}