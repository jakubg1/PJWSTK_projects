<!--
Code written as part of PJWSTK lessons
Task 2: Hotel reservation form
-->

<?php
    // segment 1: formularz
    if (!isset($_GET["people"])) {
        echo "<h2>Rezerwacja hotelu</h2>";
        echo "<form action=\"task2.php\" method=\"get\">";
        echo "<label for=\"people\">Ilość osób:</label>";
        echo "<select name=\"people\">";
        echo "<option value=\"1\">1</option>";
        echo "<option value=\"2\">2</option>";
        echo "<option value=\"3\">3</option>";
        echo "<option value=\"4\">4</option>";
        echo "</select>";
        echo "<br/>";
        echo "<label for=\"name[0]\">Imię:</label>";
        echo "<input type=\"text\" name=\"name[0]\" required>";
        echo "<br/>";
        echo "<label for=\"surname[0]\">Nazwisko:</label>";
        echo "<input type=\"text\" name=\"surname[0]\" required>";
        echo "<br/>";
        echo "<label for=\"address\">Adres:</label>";
        echo "<input type=\"text\" name=\"address\" required>";
        echo "<br/>";
        echo "<label for=\"creditnumber\">Numer karty kredytowej:</label>";
        echo "<input type=\"text\" name=\"creditnumber\" required>";
        echo "<br/>";
        echo "<label for=\"email\">E-mail:</label>";
        echo "<input type=\"email\" name=\"email\" required>";
        echo "<br/>";
        echo "<label for=\"date\">Data pobytu:</label>";
        echo "<input type=\"date\" name=\"date\" required>";
        echo "<br/>";
        echo "<label for=\"time\">Godzina przyjazdu:</label>";
        echo "<input type=\"time\" name=\"time\" required>";
        echo "<br/>";
        echo "<label for=\"kidbed\">Łóżko dla dziecka</label>";
        echo "<input type=\"checkbox\" name=\"kidbed\">";
        echo "<br/>";
        echo "<label for=\"ac\">Klimatyzacja</label>";
        echo "<input type=\"checkbox\" name=\"ac\">";
        echo "<br/>";
        echo "<label for=\"smoke\">Popielniczka dla palacza</label>";
        echo "<input type=\"checkbox\" name=\"smoke\">";
        echo "<br/>";
        echo "<input type=\"submit\" value=\"Zamów rezerwację\">";
        echo "</form>";
        return;
    }
    // segment 2: dodatkowe dane
    $people = intval($_GET["people"]);
    if (sizeof($_GET["name"]) < $people) {
        echo "<h2>Dane dodatkowych osób</h2>";
        echo "<form action=\"task2.php\" method=\"get\">";
        for ($i = sizeof($_GET["name"]); $i < $people; $i++) {
            echo "<h3>Osoba " . ($i + 1) . "</h3>";
            echo "<label for=\"name[$i]\">Imię:</label>";
            echo "<input type=\"text\" name=\"name[$i]\" required>";
            echo "<br/>";
            echo "<label for=\"surname[$i]\">Nazwisko:</label>";
            echo "<input type=\"text\" name=\"surname[$i]\" required>";
            echo "<br/>";
        }
        // carry parameters over
        echo "<input type=\"hidden\" name=\"people\" value=\"" . $_GET["people"] . "\">";
        echo "<input type=\"hidden\" name=\"name[0]\" value=\"" . $_GET["name"][0] . "\">";
        echo "<input type=\"hidden\" name=\"surname[0]\" value=\"" . $_GET["surname"][0] . "\">";
        echo "<input type=\"hidden\" name=\"address\" value=\"" . $_GET["address"] . "\">";
        echo "<input type=\"hidden\" name=\"creditnumber\" value=\"" . $_GET["creditnumber"] . "\">";
        echo "<input type=\"hidden\" name=\"email\" value=\"" . $_GET["email"] . "\">";
        echo "<input type=\"hidden\" name=\"date\" value=\"" . $_GET["date"] . "\">";
        echo "<input type=\"hidden\" name=\"time\" value=\"" . $_GET["time"] . "\">";
        if (isset($_GET["kidbed"])) echo "<input type=\"hidden\" name=\"kidbed\" value=\"on\">";
        if (isset($_GET["ac"])) echo "<input type=\"hidden\" name=\"ac\" value=\"on\">";
        if (isset($_GET["smoke"])) echo "<input type=\"hidden\" name=\"smoke\" value=\"on\">";
        //
        echo "<input type=\"submit\" value=\"Zamów rezerwację\">";
        echo "</form>";
        return;
    }
    // segment 3: podsumowanie rezerwacji
    $kidbed = isset($_GET["kidbed"]) ? "tak" : "nie";
    $ac = isset($_GET["ac"]) ? "tak" : "nie";
    $smoke = isset($_GET["smoke"]) ? "tak" : "nie";
    echo "<h2>Podsumowanie rezerwacji</h2>";
    echo "Ilość osób: <b>" . $_GET["people"] . "</b><br>";
    echo "Osoby:<br>";
    for ($i = 0; $i < $people; $i++) {
        echo "<b>" . $_GET["name"][$i] . " " . $_GET["surname"][$i] . "</b><br>";
    }
    echo "Adres: <b>" . $_GET["address"] . "</b><br>";
    echo "Numer karty: <b>" . $_GET["creditnumber"] . "</b><br>";
    echo "E-mail: <b>" . $_GET["email"] . "</b><br>";
    echo "Data pobytu: <b>" . $_GET["date"] . "</b><br>";
    echo "Godzina przyjazdu: <b>" . $_GET["time"] . "</b><br>";
    echo "Łóżko dla dziecka: <b>" . $kidbed . "</b><br>";
    echo "Klimatyzacja: <b>" . $ac . "</b><br>";
    echo "Popielniczka dla palacza: <b>" . $smoke . "</b><br>";
?>