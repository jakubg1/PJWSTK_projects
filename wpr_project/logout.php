<?php
include "functions.php";

html_start("Wylogowanie");
?>

Trwa wylogowywanie...
<div id="status" hidden="true"></div>
<a href="index.php">Strona główna</a>

<script>
    function error(message) {
        $("#status").show();
        $("#status").text(message);
    }

    ajax(
        "endpoints/user/logout.php",
        null,
        function(response) {
            redirect("index.php?logout=1");
        },
        function(response) {
            let errors = {
                403: "Błąd: Nie byłeś zalogowany!"
            };
            error(xhrError(response, errors));
        }
    );
</script>

<?php
html_end();
?>