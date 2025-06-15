<?php
/*
user/register.php

POST parameters:
- user - username to be registered
- password - password

Status codes:
- 200 - user created successfully
- 400 - incorrect data
- 409 - username already exists
- 500 - database error
*/

include_once "../../functions.php";

if (empty($_POST["user"]) || empty($_POST["password"])) {
    http_response_code(400);
    return;
}

if (db_get_user_by_name($_POST["user"])) {
    http_response_code(409);
    return;
}

$user = User::create($_POST["user"], $_POST["password"]);
$result = db_save_user($user);
if (!$result) {
    http_response_code(500);
    return;
}