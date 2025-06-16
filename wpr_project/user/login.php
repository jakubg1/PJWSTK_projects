<?php
include "../functions.php";

html_start("Zaloguj się");

html_status_box();
html_title("Zaloguj się");
$fields = [
    ["id" => "user", "type" => "text", "label" => "Nazwa użytkownika", "required" => true],
    ["id" => "password", "type" => "password", "label" => "Hasło", "required" => true],
    ["type" => "submit", "value" => "Załóż pokój"]
];
html_form("form", "/endpoints/user/login.php", $fields);
echo "<a href='../index.php'>Strona główna</a>";

html_end();
?>

<script>
    if (getURLParam("register")) {
        status("Konto założone poprawnie! Możesz się teraz zalogować.", true);
    }

    registerForm(
        "form",
        function(formData) {
            return true;
        },
        function(response) {
            //status("Zalogowałeś się!", true);
            //$("#form").hide();
            redirect("/index.php");
        },
        function(response) {
            let errors = {
                401: "Podałeś nieprawidłowe hasło.",
                404: "Użytkownik z tą nazwą nie istnieje."
            };
            status(xhrError(response, errors));
        }
    );
</script>