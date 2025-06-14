<?php
include "functions.php";

html_start();

echo "Test tworzenia nowego konta.<br/>";

$user = User::create("test", "123456");
$result = $user->save();
echo "Hasło poprawne: " . $user->check_password("") . "<br/>";
echo "Hasło poprawne: " . $user->check_password("123456") . "<br/>";

if ($result) {
    echo "Użytkownik poprawnie dodany!<br/>";
    $user2 = User::get($user->get_id());
} else {
    echo "Użytkownik już był! Sprawdzam po nazwie!<br/>";
    $user2 = User::get_by_name("test");
}
echo "Hasło poprawne: " . $user2->check_password("") . "<br/>";
echo "Hasło poprawne: " . $user2->check_password("123456") . "<br/>";

html_end();