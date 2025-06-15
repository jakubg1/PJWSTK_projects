<?php
/*
user/login.php

POST parameters:
- user - username to be registered
- password - password

Status codes:
- 200 - user login successful
- 400 - incorrect data
- 401 - incorrect password
- 404 - user does not exist
*/

include_once "../../functions.php";

session_start();

if (empty($_POST["user"]) || empty($_POST["password"])) {
    http_response_code(400);
    return;
}

$user = User::get_by_name($_POST["user"]);
if (empty($user)) {
    http_response_code(404);
    return;
}

$success = $user->check_password($_POST["password"]);
if (!$success) {
    http_response_code(401);
    return;
}

$_SESSION["user_id"] = $user->get_id();