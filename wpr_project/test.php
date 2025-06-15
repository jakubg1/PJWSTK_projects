<?php
include "functions.php";

html_start();

echo "Test tworzenia nowego konta.<br/>";

$user = User::create("test", "123456");
$result = db_save_user($user);
echo "Hasło poprawne: " . $user->check_password("") . "<br/>";
echo "Hasło poprawne: " . $user->check_password("123456") . "<br/>";

if ($result) {
    echo "Użytkownik poprawnie dodany!<br/>";
    $user2 = db_get_user($user->get_id());
} else {
    echo "Użytkownik już był! Sprawdzam po nazwie!<br/>";
    $user2 = db_get_user_by_name("test");
}
echo "Hasło poprawne: " . $user2->check_password("") . "<br/>";
echo "Hasło poprawne: " . $user2->check_password("123456") . "<br/>";

html_end();