<?php
include "../functions.php";

html_start("Wylogowanie");
?>

<div id="status" hidden="true"></div>
<h1>Trwa wylogowywanie...</h1>
<a href="../index.php">Strona główna</a>

<script>
    ajax(
        "/endpoints/user/logout.php",
        null,
        function(response) {
            redirect("/index.php?logout=1");
        },
        function(response) {
            let errors = {
                403: "Błąd: Nie byłeś zalogowany!"
            };
            status(xhrError(response, errors));
        }
    );
</script>

<?php
html_end();
?>