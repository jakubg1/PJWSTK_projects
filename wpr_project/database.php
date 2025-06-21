<?php
function db_connect() {
    $HOST = "localhost";
    $DBNAME = "gameserver";
    $USER = "root";
    $PASS = "";

    $dsn = "mysql:host=" . $HOST . ";dbname=" . $DBNAME . "";

    try {
        return new PDO($dsn, $USER, $PASS, [PDO::ATTR_PERSISTENT => true]);
    } catch (Exception $e) {
        html_start("Database error");
        echo "Nie udało się połączyć z bazą danych! Czy zainicjalizowałeś już bazę?";
        html_end();
    }
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

// Turns for example: ["key1" => "value1", "key2" => 2] into "key1 = :key1, key2 = :key2".
function db_get_sql_assigns($params) {
    $sql = "";
    $first_param = true;
    foreach ($params as $key => $value) {
        if ($first_param)
            $first_param = false;
        else
            $sql .= ", ";
        $sql .= $key . " = :" . $key;
    }
    return $sql;
}

// Turns for example: ["key1" => "value1", "key2" => 2] into "key1 = :key1 AND key2 = :key2".
function db_get_sql_assigns_and($params) {
    $sql = "";
    $first_param = true;
    foreach ($params as $key => $value) {
        if ($first_param)
            $first_param = false;
        else
            $sql .= " AND ";
        $sql .= $key . " = :" . $key;
    }
    return $sql;
}

// Turns for example: ["key1" => "value1", "key2" => 2] into ":key1, :key2".
function db_get_sql_placeholders($params) {
    $sql = "";
    $first_param = true;
    foreach ($params as $key => $value) {
        if ($first_param)
            $first_param = false;
        else
            $sql .= ", ";
        $sql .= ":" . $key;
    }
    return $sql;
}

// If a field is specified in `$where`, it will be checked against. Otherwise, it will be updated.
// For example, `$params` = ["id" => 15, "a" => 2, "b" = 5] and `$where` = ["id" => true] will update `a` and `b` values for `id`=15.
function db_update($table, $params, $where) {
    $dbc = db_connect();
    $sql = "UPDATE " . $table . " SET " . db_get_sql_assigns($params) . " WHERE " . db_get_sql_assigns_and($where);
    $sth = $dbc->prepare($sql);
    return $sth->execute($params);
}

function db_insert($table, $params) {
    $dbc = db_connect();
    $sql = "INSERT INTO " . $table . " VALUES (" . db_get_sql_placeholders($params) . ")";
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

function db_remove($table, $params) {
    $dbc = db_connect();
    $sql = "DELETE FROM " . $table . " WHERE " . db_get_sql_assigns_and($params);
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
        $params = [];
        // Make sure only specified keys land as parameters. This is a requirement of PDO.
        foreach ($keys as $key) {
            $params[$key] = $data[$key];
        }
        $result = db_update($table, $params, ["id" => true]);
        if (!$result)
            return false;
    } else {
        // Object doesn't exist. Create a new record in the database.
        $params = [];
        // Make sure only specified keys land as parameters. This is a requirement of PDO.
        foreach ($keys as $key) {
            $params[$key] = $data[$key];
        }
        $result = db_insert($table, $params);
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
    //         "subfields" => ["last_heartbeat_at"]    // NOTE: If there is a direct value instead of an array in the class, set this as the table's column name.
    //     ]
    // ]
    // Keys in this field are the keys in packed data which need to be stored in their own tables.
    // Then, `table` is the associative table name, `field` and `subfield` are field names (taken from the
    // object ID and packed data keys, respectively), and `subfields` is a list of additional fields.
    foreach ($arrays as $field => $array) {
        // Fetch all subIDs for the provided ID.
        $sql = "SELECT " . $array["subfield"] . " FROM " . $array["table"] . " WHERE " . $array["field"] . " = ?"; // i.e. "SELECT user_id FROM room_players WHERE room_id = ?"
        $rows = db_select($sql, [$data["id"]]);
        // Now that we know about all IDs existing in the database, update or remove all entries depending on whether or not they are present in our data.
        foreach ($rows as $row) {
            $id = intval($row[$array["subfield"]]);
            if (isset($data[$field][$id])) {
                // Value exists in our data. Update the associated key.
                $params = [$array["field"] => $data["id"], $array["subfield"] => $id];
                if (gettype($array["subfields"]) == "string") {
                    // Singular key: value is direct.
                    $params[$array["subfields"]] = $data[$field][$id];
                } else {
                    // Multiple keys: check all provided subfields.
                    foreach ($array["subfields"] as $subfield) {
                        $params[$subfield] = $data[$field][$id][$subfield];
                    }
                }
                //echo "\n   >> * (" . $data["id"] . ", " . $id . ")";
                db_update($array["table"], $params, [$array["field"] => true, $array["subfield"] => true]);
            } else {
                // Value does not exist in our data. Remove the associated key.
                //echo "\n   >> - (" . $data["id"] . ", " . $id . ")";
                db_remove($array["table"], [$array["field"] => $data["id"], $array["subfield"] => $id]); // i.e. db_remove("room_players", ["room_id" => ..., "user_id" => ...])
            }
        }
        // Finally, if there are new rows, we need to add them to the database.
        foreach ($data[$field] as $subid => $subdata) {
            $exists = false;
            foreach ($rows as $row) {
                $id = $row[$array["subfield"]];
                if ($id == $subid) {
                    $exists = true;
                    break;
                }
            }
            if ($exists) {
                continue;
            }
            // Value exists in our data, but not in the database. Let's add it.
            $params = [$array["field"] => $data["id"], $array["subfield"] => $subid];
            if (gettype($array["subfields"]) == "string") {
                // Singular key: value is direct.
                $params[$array["subfields"]] = $subdata;
            } else {
                // Multiple keys: check all provided subfields.
                foreach ($array["subfields"] as $subfield) {
                    $params[$subfield] = $subdata[$subfield];
                }
            }
            //echo "\n   >> + (" . $data["id"] . ", " . $subid . ")";
            db_insert($array["table"], $params);
        }
    }
    return true;
}