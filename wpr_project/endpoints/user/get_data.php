<?php
/*
/endpoints/user/get_data.php

Retrieves data for the currently logged in user.

No POST parameters, session must be active!

Status codes:
- 200 - retrieval successful
- 404 - user does not exist

Returned data:
- {"user": <packed user data>}
*/

http_response_code(500);

include "../../functions.php";

session_start();

$user = get_user();

$result = [];
// Fetch the room data.
$result["user"] = $user->pack();
echo json_encode($result);

http_response_code(200);