<?php
// WORK IN PROGRESS!!!
// DO NOT RATE until this notice is removed!

include_once "functions.php";
include_once "database.php";
include_once "objects/user.php";

html_start();
echo "Witaj na portalu z dwoma grami!<br/>";
echo "Jeżeli jeszcze nie masz konta, zarejestruj się aby móc rozmawiać na czacie i śledzić swoje statystyki!<br/>";
echo "Możesz też grać jako gość.<br/>";

$user = User::create("test", "123456");
db_save_user($user);
echo "Hasło poprawne: " . $user->check_password("") . "<br/>";
echo "Hasło poprawne: " . $user->check_password("123456") . "<br/>";

$user2 = db_get_user($user->get_id());
echo "Hasło poprawne: " . $user2->check_password("") . "<br/>";
echo "Hasło poprawne: " . $user2->check_password("123456") . "<br/>";

html_end();