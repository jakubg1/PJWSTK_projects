<?php
include "../functions.php";

html_start("Dołączanie do pokoju");

html_title("Trwa dołączanie do pokoju...");
html_status_box();
$fields = [
    ["id" => "id", "value" => $_GET["id"]],
    ["id" => "password", "type" => "password", "label" => "Hasło", "required" => true],
    ["type" => "submit", "value" => "Dołącz"]
];
html_form("form", "/endpoints/room/join.php", $fields);
echo "<a href='../index.php'>Strona główna</a>";

html_end();
?>

<script>
    // If the room has no password set, we will not display the form by default.
    $("#form").hide();

    // Try sending a request and see what happens.
    // If we get a 401, show the form.
    ajax(
        "/endpoints/room/join.php",
        {
            "id": getURLParam("id")
        },
        function(response) {
            redirect("/room/game.php");
        },
        function(response) {
            if (response.status == 401) {
                showForm();
            } else {
                let errors = {
                    403: "Nieprawidłowe hasło!",
                    404: "Nie znaleziono pokoju!",
                    409: "Ten pokój jest pełny!"
                };
                status(xhrError(response, errors));
                $("h1").text("Nie udało się dołączyć do pokoju!");
            }
        }
    );

    function showForm() {
        $("h1").text("Dołącz do pokoju");
        $("#form").show();

        registerForm(
            "form",
            function(formData) {
                return true;
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
            }
        );
    }
</script>