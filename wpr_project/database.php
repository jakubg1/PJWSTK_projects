<?php
function db_connect() {
    $HOST = "localhost";
    $DBNAME = "gameserver";
    $USER = "root";
    $PASS = "";

    $dsn = "mysql:host=" . $HOST . ";dbname=" . $DBNAME . "";

    return new PDO($dsn, $USER, $PASS, [PDO::ATTR_PERSISTENT => true]);
}

function db_select_one($sql, $params) {
    $dbc = db_connect();
    $sth = $dbc->prepare($sql);
    $sth->execute($params);
    return $sth->fetch(PDO::FETCH_ASSOC);
}

function db_select($sql, $params) {
    $dbc = db_connect();
    $sth = $dbc->prepare($sql);
    $sth->execute($params);
    return $sth->fetchAll(PDO::FETCH_ASSOC);
}

function db_update($sql, $params) {
    $dbc = db_connect();
    $sth = $dbc->prepare($sql);
    return $sth->execute($params);
}

function db_insert($sql, $params) {
    $dbc = db_connect();
    $sth = $dbc->prepare($sql);
    try {
        $result = $sth->execute($params);
        if (!$result)
            return false;
        return $dbc->lastInsertId();
    } catch (Exception $e) {
        // We go here if the database rejects the query (for example, a duplicate field)
        return false;
    }
}

// Saves an object to the database.
// If there exists a record in the database with all primary keys matching their values, that record is updated.
// Otherwise, a new record is created in the database.
function db_save_object($object, $table, $keys, $primary_keys = ["id"]) {
    // $primary_keys is not checked right now. This will become relevant when multi-primary-key tables/classes will be introduced.
    $data = $object->pack();
    if (isset($data["id"])) {
        // Object exists. Update it.
        $sql = "UPDATE " . $table . " SET ";
        for ($i = 0; $i < count($keys); $i++) {
            if ($i > 0)
                $sql .= ", ";
            $sql .= $keys[$i] . " = :" . $keys[$i];
        }
        $sql .= " WHERE id = :id";
        return db_update($sql, $data);
    } else {
        // Object doesn't exist. Create a new record in the database.
        unset($data["id"]);
        $sql = "INSERT INTO " . $table . " VALUES (NULL";
        foreach ($keys as $key) {
            if ($key != "id") {
                $sql .= ", :" . $key;
            }
        }
        $sql .= ")";
        $result = db_insert($sql, $data);
        if (!$result)
            return false;
        // Populate the ID field.
        $object->set_id($result);
        return true;
    }
}