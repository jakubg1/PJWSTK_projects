<?php
class QueuedEvent {
    private $id;
    private $user_id;
    private $type;
    private $payload;

    public function get_id() {
        return $this->id;
    }

    public function set_id($id) {
        $this->id = $id;
    }

    public function get_user() {
        return User::get($this->user_id);
    }

    public function set_user($user) {
        $this->user_id = $user->get_id();
    }

    public function get_type() {
        return $this->type;
    }

    public function set_type($type) {
        $this->type = $type;
    }

    public function get_payload() {
        return unserialize($this->payload);
    }

    public function set_payload($payload) {
        $this->payload = serialize($payload);
    }

    // Creates a new event
    public static function create($user, $type) {
        $obj = new QueuedEvent();
        $obj->id = null;
        $obj->set_user($user);
        $obj->type = $type;
        return $obj;
    }

    // Retrieves a event by ID
    public static function get($id) {
        $row = db_select_one("SELECT * FROM queued_events WHERE id = ?", [$id]);
        return QueuedEvent::load($row);
    }

    // Retrieves a list of event by user
    public static function get_list_by_user($user) {
        $rows = db_select("SELECT * FROM queued_events WHERE user_id = ?", [$user->get_id()]);
        $events = [];
        foreach ($rows as $row) {
            $events[] = QueuedEvent::get($row["id"]);
        }
        return $events;
    }

    // Saves the event to database
    public function save() {
        return db_save_object($this, "queued_events", ["id", "user_id", "type", "payload"]);
    }

    // Removes the event from database.
    public function delete() {
        db_remove("queued_events", ["id" => $this->id]);
        $this->id = null;
    }

    // Loads the event from given database row
    private static function load($row) {
        if (!$row) {
            return null;
        }
        $msg = new QueuedEvent();
        $msg->id = $row["id"];
        $msg->user_id = $row["user_id"];
        $msg->type = $row["type"];
        $msg->payload = $row["payload"];
        return $msg;
    }

    // Packs the event data for ease of use in database functions
    public function pack() {
        return [
            "id" => $this->id,
            "user_id" => $this->user_id,
            "type" => $this->type,
            "payload" => $this->payload
        ];
    }
}