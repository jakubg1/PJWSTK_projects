<?php
include "../functions.php";

html_start("Dołączanie do pokoju");

html_title("Trwa dołączanie do pokoju...");
html_status_box();
echo "<a href='../index.php'>Strona główna</a>";

html_end();
?>

<script>
    ajax(
        "/endpoints/room/join.php",
        {
            "id": getURLParam("id")
        },
        function(response) {
            redirect("/room/game.php");
        },
        function(response) {
            let errors = {
                403: "Nieprawidłowe hasło!",
                404: "Nie znaleziono pokoju!",
                409: "Ten pokój jest pełny!"
            };
            status(xhrError(response, errors));
            $("h1").text("Nie udało się dołączyć do pokoju!");
        }
    );
</script>