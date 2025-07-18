<?php
include "../functions.php";

html_start("Rejestracja");

html_status_box();
html_title("Zarejestruj się");
$fields = [
    ["id" => "user", "type" => "text", "label" => "Nazwa użytkownika", "required" => true],
    ["id" => "email", "type" => "email", "label" => "Adres e-mail", "required" => true],
    ["id" => "password", "type" => "password", "label" => "Hasło", "required" => true],
    ["id" => "password_rep", "type" => "password", "label" => "Powtórz hasło", "required" => true],
    ["type" => "submit", "value" => "Załóż konto"]
];
html_form("form", "/endpoints/user/register.php", $fields);
echo "<a href='../index.php'>Strona główna</a>";

html_end();
?>

<script>
    registerForm(
        "form",
        function(formData) {
            if (formData.get("user").length > 32) {
                status("Nazwa użytkownika nie może być dłuższa niż 32 znaki!");
                return false;
            }
            if (formData.get("user").substring(0, 1) == "_") {
                status("Nazwa użytkownika nie może zaczynać się od '_'!");
                return false;
            }
            if (formData.get("password").length < 6) {
                status("Hasło musi mieć przynajmniej 6 znaków!");
                return false;
            }
            if (formData.get("password") != formData.get("password_rep")) {
                status("Hasła się nie zgadzają! Upewnij się, czy na pewno dobrze wpisałeś hasła.");
                return false;
            }
            return true;
        },
        function(response) {
            redirect("/user/login.php?register=1");
        },
        function(response) {
            let errors = {
                409: "Ta nazwa użytkownika lub e-mail są już zajęte! Musisz wybrać inne."
            };
            status(xhrError(response, errors));
        }
    );
</script>