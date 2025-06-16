<?php
include "../functions.php";

// Validate the GET parameter and check if we're a logged in user.
if (!isset($_GET["game"]) || !is_game_type_supported($_GET["game"])) {
    echo "Nie ma takiej gry!";
    return;
}
session_start();
ensure_login();

html_start("Załóż pokój");

html_status_box();
echo "<h1>Załóż nowy pokój</h1>";
echo "<h2>Gra: " . translate_game_type($_GET["game"]) . "</h2>";
$fields = [
    ["id" => "name", "type" => "text", "label" => "Nazwa pokoju", "required" => true],
    ["id" => "password", "type" => "password", "label" => "Hasło"],
    ["id" => "game_type", "value" => $_GET["game"]],
    ["type" => "submit", "value" => "Załóż pokój"]
];
html_form("form", "/endpoints/room/create.php", $fields);
echo "<a href='../index.php'>Strona główna</a>";

html_end();
?>

<script>
    registerForm(
        "form",
        function(formData) {
            if (formData.get("name").length > 32) {
                status("Nazwa pokoju nie może być dłuższa niż 32 znaki!");
                return false;
            }
            return true;
        },
        function(response) {
            redirect("/room/game.php");
        },
        function(response) {
            let errors = {
                403: "Nie jesteś zalogowany. Zaloguj się i spróbuj ponownie."
            };
            status(xhrError(response, errors));
        }
    );
</script>