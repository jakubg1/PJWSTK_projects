<?php
function db_connect() {
    $HOST = "localhost";
    $DBNAME = "gameserver";
    $USER = "root";
    $PASS = "";

    $dsn = "mysql:host=" . $HOST . ";dbname=" . $DBNAME . "";

    return new PDO($dsn, $USER, $PASS, [PDO::ATTR_PERSISTENT => true]);
}

function db_select_one($sql, $params = []) {
    $dbc = db_connect();
    $sth = $dbc->prepare($sql);
    $sth->execute($params);
    return $sth->fetch(PDO::FETCH_ASSOC);
}

function db_select($sql, $params = []) {
    $dbc = db_connect();
    $sth = $dbc->prepare($sql);
    $sth->execute($params);
    return $sth->fetchAll(PDO::FETCH_ASSOC);
}

function db_update($sql, $params = []) {
    $dbc = db_connect();
    $sth = $dbc->prepare($sql);
    return $sth->execute($params);
}

function db_insert($sql, $params = []) {
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

function db_remove($sql, $params = []) {
    $dbc = db_connect();
    $sth = $dbc->prepare($sql);
    return $sth->execute($params);
}

// Saves an object to the database.
// If there exists a record in the database with all primary keys matching their values, that record is updated.
// Otherwise, a new record is created in the database.
function db_save_object($object, $table, $keys, $arrays = []) {
    $data = $object->pack();
    if (isset($data["id"])) {
        // Object exists. Update it.
        $sql = "UPDATE " . $table . " SET ";
        $params = [];
        for ($i = 0; $i < count($keys); $i++) {
            $key = $keys[$i];
            if ($i > 0)
                $sql .= ", ";
            $sql .= $key . " = :" . $key;
            // Make sure only specified keys land as parameters. This is a requirement of PDO.
            $params[$key] = $data[$key];
        }
        $sql .= " WHERE id = :id";
        $result = db_update($sql, $params);
        if (!$result)
            return false;
    } else {
        // Object doesn't exist. Create a new record in the database.
        $sql = "INSERT INTO " . $table . " VALUES (NULL";
        $params = [];
        foreach ($keys as $key) {
            if ($key != "id") {
                $sql .= ", :" . $key;
                // Make sure only specified keys land as parameters. This is a requirement of PDO.
                $params[$key] = $data[$key];
            }
        }
        $sql .= ")";
        $result = db_insert($sql, $params);
        if (!$result)
            return false;
        // Populate the ID field.
        $object->set_id($result);
        $data["id"] = $result;
    }
    // Optionally, we still need to update values in other tables if there are arrays defined in $arrays.
    // Each array stored in the object needs its own table due to how N:N relationships work.
    // For example, here's some data from the packed field:
    // $data["players"] = [
    //     [108] => ["last_heartbeat_at" => "2025-06-17 19:40:02"],
    //     [237] => ["last_heartbeat_at" => "2025-06-17 19:40:04"]
    // ]
    // If the room hosting this data has ID=39, the room_players associative table would look like this:
    // room_id    user_id    last_heartbeat_at
    // 39         108        2025-06-17 19:40:02
    // 39         237        2025-06-17 19:40:04
    // In order to achieve this, the $arrays field needs to be configured like this:
    // $arrays = [
    //     "players" => [
    //         "table" => "room_players",
    //         "field" => "room_id",
    //         "subfield" => "user_id",
    //         "subfields" => ["last_heartbeat_at"]
    //     ]
    // ]
    // Keys in this field are the keys in packed data which need to be stored in their own tables.
    // Then, `table` is the associative table name, `field` and `subfield` are field names (taken from the
    // object ID and packed data keys, respectively), and `subfields` is a list of additional fields.
    //
    // Because we're lazy, we will always remove all fields and add them from scratch.
    // If we were to do this correctly, we would need to check the presence of each key and perform
    // UPDATEs on changed keys, DELETEs on removed ones, and INSERTs on added ones.
    foreach ($arrays as $field => $array) {
        // Remove all associated keys.
        $sql = "DELETE FROM " . $array["table"] . " WHERE " . $array["field"] . " = ?";
        db_remove($sql, [$data["id"]]);
        // Insert all of them all over again.
        $sql = "INSERT INTO " . $array["table"] . " VALUES (:field, :subfield";
        foreach ($array["subfields"] as $subfield) {
            $sql .= ", :" . $subfield;
        }
        $sql .= ")";
        foreach ($data[$field] as $subid => $subdata) {
            $params = ["field" => $data["id"], "subfield" => $subid];
            foreach ($array["subfields"] as $subfield) {
                $params[$subfield] = $subdata[$subfield];
            }
            db_insert($sql, $params);
        }
    }
    return true;
}