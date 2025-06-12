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

function db_get_user($user_id) {
    $dbc = db_connect();
    $sql = "SELECT * FROM users WHERE id = ?";
    $sth = $dbc->prepare($sql);
    $sth->execute([$user_id]);
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
        $sql = "UPDATE users VALUES (:id, :name, :type, :password, :created_at, :last_active_at) WHERE id = :id";
        $sth = $dbc->prepare($sql);
        $sth->execute($data);
    } else {
        // User does not exist, because it has no ID assigned.
        $sql = "INSERT INTO users VALUES (NULL, :name, :type, :password, :created_at, :last_active_at)";
        $sth = $dbc->prepare($sql);
        unset($data["id"]);
        $sth->execute($data);
        // Populate the ID field.
        $user->set_id($dbc->lastInsertId());
    }
}