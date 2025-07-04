<?php
/*
/endpoints/user/logout.php

No POST parameters, session must be active!

Status codes:
- 200 - user logged out successfully
- 403 - user is not logged in
*/

http_response_code(500);

include "../../functions.php";

session_start();

if (!isset($_SESSION["user_id"])) {
    http_response_code(403);
    return;
}

session_destroy();

http_response_code(200);