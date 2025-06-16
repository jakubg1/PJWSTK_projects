<?php
include "../functions.php";

html_start("Wylogowanie");

html_status_box();
html_title("Trwa wylogowywanie...");
echo "<a href='../index.php'>Strona główna</a>";

html_end();
?>

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