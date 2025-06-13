<?php
include_once "functions.php";

html_start("Zaloguj się");
?>

<div id="status" hidden="true"></div>
Zaloguj się
<form id="login" action="endpoints/user_login.php" method="POST">
    <label for="user">Nazwa użytkownika: *</label>
    <input type="text" id="user" name="user" required="true">
    <br/>
    <label for="password">Hasło: *</label>
    <input type="password" id="password" name="password" required="true">
    <br/>
    <input type="submit" value="Zaloguj się">
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

    if (getURLParam("register")) {
        status("Konto założone poprawnie! Możesz się teraz zalogować.");
    }

    registerForm(
        "login",
        function(formData) {
            return true;
        },
        function(response) {
            //status("Zalogowałeś się!", true);
            //$("#login").hide();
            redirect("index.php");
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

<?php
html_end();
?>