<?php
include "functions.php";

html_start("Rejestracja");
?>

<div id="status" hidden="true"></div>
Zarejestruj się
<form id="register" action="endpoints/user/register.php" method="POST">
    <label for="user">Nazwa użytkownika: *</label>
    <input type="text" id="user" name="user" required="true">
    <br/>
    <label for="password">Hasło: *</label>
    <input type="password" id="password" name="password" required="true">
    <br/>
    <label for="password">Powtórz hasło: *</label>
    <input type="password" id="password_rep" name="password_rep" required="true">
    <br/>
    <input type="submit" value="Załóż konto">
</form>
<a href="index.php">Strona główna</a>

<script>
    function status(message, isSuccess = false) {
        let status = $("#status");
        status.show();
        status.removeClass("success failure");
        status.addClass(isSuccess ? "success" : "failure");
        status.text(message);
    }

    registerForm(
        "register",
        function(formData) {
            if (formData.get("user").length > 32) {
                status("Nazwa użytkownika nie może być dłuższa niż 32 znaki!");
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
            redirect("login.php?register=1");
        },
        function(response) {
            let errors = {
                409: "Ta nazwa użytkownika jest już zajęta! Musisz wybrać inną."
            };
            status(xhrError(response, errors));
        }
    );
</script>

<?php
html_end();
?>