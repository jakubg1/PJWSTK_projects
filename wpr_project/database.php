<?php
include_once "objects/user.php";

function db_connect() {
    $HOST = "localhost";
    $DBNAME = "gameserver";
    $USER = "root";
    $PASS = "";

    $dsn = "mysql:host=" . $HOST . ";dbname=" . $DBNAME . "";

    return new PDO($dsn, $USER, $PASS, [PDO::ATTR_PERSISTENT => true]);
}

function db_get_user($id) {
    $dbc = db_connect();
    $sql = "SELECT * FROM users WHERE id = ?";
    $sth = $dbc->prepare($sql);
    $sth->execute([$id]);
    $row = $sth->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        return User::load($row);
    } else {
        return null;
    }
}

function db_get_user_by_name($name) {
    $dbc = db_connect();
    $sql = "SELECT * FROM users WHERE name = ?";
    $sth = $dbc->prepare($sql);
    $sth->execute([$name]);
    $row = $sth->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        return User::load($row);
    } else {
        return null;
    }
}

function db_save_user(User $user) {
    $dbc = db_connect();
    $data = $user->pack();
    if (isset($data["id"])) {
        // User exists, because it has an ID assigned.
        $sql = "UPDATE users SET id = :id, name = :name, type = :type, password = :password, created_at = :created_at, last_active_at = :last_active_at WHERE id = :id";
        $sth = $dbc->prepare($sql);
        return $sth->execute($data);
    } else {
        // User does not exist, because it has no ID assigned.
        $sql = "INSERT INTO users VALUES (NULL, :name, :type, :password, :created_at, :last_active_at)";
        $sth = $dbc->prepare($sql);
        unset($data["id"]);
        try {
            $result = $sth->execute($data);
            // Populate the ID field.
            $user->set_id($dbc->lastInsertId());
            return $result;
        } catch (Exception $e) {
            // We go here if the database rejects the query (for example, a duplicate field)
            return false;
        }
    }
}