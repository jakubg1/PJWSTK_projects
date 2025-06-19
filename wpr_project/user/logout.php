<?php
include "../functions.php";

html_start("Wylogowywanie");

html_title("Trwa wylogowywanie...");
html_status_box();
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
            if (response.status == 401) {
                showForm();
            } else {
                let errors = {
                    403: "Nie byłeś zalogowany!"
                };
                status(xhrError(response, errors));
                $("h1").text("Nie udało się wylogować!");
            }
        }
    );
</script>