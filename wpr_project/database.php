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