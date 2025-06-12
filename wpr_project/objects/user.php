<?php
class User {
    private $id;
    private $name;
    private $type;
    private $hashed_password;
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

    public function set_password($password) {
        $this->hashed_password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function check_password($password) {
        return password_verify($password, $this->hashed_password);
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

    // Creates a new user
    public static function create($name, $password) {
        $user = new User();
        $user->id = NULL;
        $user->name = $name;
        $user->type = "user";
        $user->set_password($password);
        $user->created_at = date("Y-m-d H:i:s");
        $user->last_active_at = date("Y-m-d H:i:s");
        return $user;
    }

    // Loads the user from database
    public static function load($row) {
        $user = new User();
        $user->id = $row["id"];
        $user->name = $row["name"];
        $user->type = $row["type"];
        $user->hashed_password = $row["password"];
        $user->created_at = $row["created_at"];
        $user->last_active_at = $row["last_active_at"];
        return $user;
    }

    public function pack() {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "type" => $this->type,
            "password" => $this->hashed_password,
            "created_at" => $this->created_at,
            "last_active_at" => $this->last_active_at
        ];
    }
}