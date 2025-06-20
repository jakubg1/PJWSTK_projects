<?php
/*
/endpoints/user/register.php

POST parameters:
- user - username to be registered
- email - e-mail address
- password - password

Status codes:
- 200 - user created successfully
- 400 - incorrect data
- 409 - username or email already exists
- 500 - database error
*/

http_response_code(500);

include "../../functions.php";

if (empty($_POST["user"]) || substr($_POST["user"], 0, 1) == "_" || empty($_POST["email"]) || empty($_POST["password"])) {
    http_response_code(400);
    return;
}

if (User::get_by_name($_POST["user"])) {
    http_response_code(409);
    return;
}

if (User::get_by_email($_POST["email"])) {
    http_response_code(409);
    return;
}

$user = User::create($_POST["user"], $_POST["email"], $_POST["password"]);
$result = $user->save();
if (!$result) {
    http_response_code(500);
    return;
}

http_response_code(200);