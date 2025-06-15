<?php
class User {
    private $id;
    private $name;
    private $type;
    private $password;
    private $created_at;
    private $last_active_at;

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

    public function get_type() {
        return $this->type;
    }

    public function set_type($type) {
        $this->type = $type;
    }

    public function check_password($password) {
        return password_verify($password, $this->password);
    }

    public function set_password($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function get_created_at() {
        return $this->created_at;
    }

    public function get_last_active_at() {
        return $this->last_active_at;
    }

    public function set_last_active_at() {
        $this->last_active_at = date("Y-m-d H:i:s");
    }

    // Loads the user from given database row
    private static function load($row) {
        if (!$row) {
            return null;
        }
        $user = new User();
        $user->id = $row["id"];
        $user->name = $row["name"];
        $user->type = $row["type"];
        $user->password = $row["password"];
        $user->created_at = $row["created_at"];
        $user->last_active_at = $row["last_active_at"];
        return $user;
    }

    // Packs the user data for ease of use in database functions
    public function pack() {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "type" => $this->type,
            "password" => $this->password,
            "created_at" => $this->created_at,
            "last_active_at" => $this->last_active_at
        ];
    }

    // Creates a new user
    public static function create($name, $password) {
        $user = new User();
        $user->id = null;
        $user->name = $name;
        $user->type = "user";
        $user->set_password($password);
        $user->created_at = date("Y-m-d H:i:s");
        $user->last_active_at = date("Y-m-d H:i:s");
        return $user;
    }

    // Retrieves a user by ID
    public static function get($id) {
        $row = db_select_one("SELECT * FROM users WHERE id = ?", [$id]);
        return User::load($row);
    }

    // Retrieves a user by name
    public static function get_by_name($name) {
        $row = db_select_one("SELECT * FROM users WHERE name = ?", [$name]);
        return User::load($row);
    }

    // Saves the user to database
    public function save() {
        return db_save_object($this, "users", ["id", "name", "type", "password", "created_at", "last_active_at"]);
    }
}