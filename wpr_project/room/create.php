<?php
include "../functions.php";
session_start();
ensure_login();
html_start("Załóż pokój");
?>

<div id="status" hidden="true"></div>
<h1>Załóż nowy pokój</h1>
<form id="form" action="/endpoints/room/create.php" method="POST">
    <label for="name">Nazwa pokoju: *</label>
    <input type="text" id="name" name="name" required="true">
    <label for="password">Hasło:</label>
    <input type="password" id="password" name="password">
    <input type="submit" value="Załóż pokój">
</form>
<a href="../index.php">Strona główna</a>

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
            redirect("/room/game.php?id=1");
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