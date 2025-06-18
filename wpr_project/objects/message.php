<?php
class Message {
    private $id;
    private $game_id;
    private $user_id;
    private $message;
    private $sent_at;

    public function get_id() {
        return $this->id;
    }

    public function set_id($id) {
        $this->id = $id;
    }

    public function get_game() {
        return Game::get($this->game_id);
    }

    public function set_game($game) {
        $this->game_id = $game->get_id();
    }

    public function get_user() {
        return User::get($this->user_id);
    }

    public function set_user($user) {
        $this->user_id = $user->get_id();
    }

    public function get_message() {
        return $this->message;
    }

    public function set_message($message) {
        $this->message = $message;
    }

    public function get_sent_at() {
        return $this->sent_at;
    }

    // Loads the message from given database row
    private static function load($row) {
        if (!$row) {
            return null;
        }
        $msg = new Message();
        $msg->id = $row["id"];
        $msg->game_id = $row["game_id"];
        $msg->user_id = $row["user_id"];
        $msg->message = $row["message"];
        $msg->sent_at = $row["sent_at"];
        return $msg;
    }

    // Packs the message data for ease of use in database functions
    public function pack() {
        return [
            "id" => $this->id,
            "game_id" => $this->game_id,
            "user_id" => $this->user_id,
            "message" => $this->message,
            "sent_at" => $this->sent_at,
        ];
    }

    // Creates a new message
    public static function create($game, $user, $message) {
        $msg = new Message();
        $msg->id = null;
        $msg->game_id = $game->get_id();
        $msg->user_id = $user->get_id();
        $msg->message = $message;
        $msg->sent_at = get_timestamp();
        return $msg;
    }

    // Retrieves a message by ID
    public static function get($id) {
        $row = db_select_one("SELECT * FROM messages WHERE id = ?", [$id]);
        return Message::load($row);
    }

    // Saves the message to database
    public function save() {
        return db_save_object($this, "messages", ["id", "game_id", "user_id", "message", "sent_at"]);
    }
}