<h1>Struktura plików</h1>
<form action="task3.php" method="get">
    <label for="path">Ścieżka: </label>
    <input type="text" name="path">
    <label for="name">Katalog: </label>
    <input type="text" name="name">
    <label for="operation">Operacja: </label>
    <select name="operation">
        <option value="read">Listuj</option>
        <option value="delete">Usuń</option>
        <option value="create">Stwórz</option>
    </select>
    <input type="submit" value="Wykonaj">
</form>

<?php
function fsop($path, $name, $operation = "read") {
    if (empty($path)) {
        $path = ".";
    }
    if (substr($path, strlen($path) - 1, 1) != "/") {
        $path .= "/";
    }
    if (!($fd = opendir($path))) {
        echo "Nie ma katalogu: <b>" . $path . "</b>";
        return;
    }
    switch ($operation) {
        case "read":
            echo "<b>Katalog: " . $path . "</b><br/>";
            $files = scandir($path);
            foreach ($files as $file) {
                if ($file != "." && $file != "..") {
                    echo $file . "<br/>";
                }
            }
            break;
        case "create":
            if (empty($_GET["name"])) {
                echo "Nie podano nazwy folderu";
            } else {
                $result = mkdir($path . $name, 0777, true);
                if ($result) {
                    echo "Pomyślnie utworzono folder: " . $path . "<b>" . $name . "</b>";
                } else {
                    echo "Nie udało się utworzyć folderu: " . $path . "<b>" . $name . "</b>";
                }
            }
            break;
        case "delete":
            if (empty($_GET["name"])) {
                echo "Nie podano nazwy folderu";
            } else {
                $result = rmdir($path . $name);
                if ($result) {
                    echo "Pomyślnie skasowano folder: " . $path . "<b>" . $name . "</b>";
                } else {
                    echo "Nie udało się skasować folderu: " . $path . "<b>" . $name . "</b>";
                }
            }
            break;
        default:
            echo "Niewłaściwa operacja: <b>" . $operation . "</b>";
    }
    closedir($fd);
}

if (!isset($_GET["path"]) || !isset($_GET["name"]) || empty($_GET["operation"])) {
    return;
}
$path = $_GET["path"];
$name = $_GET["name"];
$operation = $_GET["operation"];
fsop($path, $name, $operation);
?>