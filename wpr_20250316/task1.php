<html>
<body>
    <h1>Kalkulator</h1>
    <form action="task1.php" method="get">
        <input type="number" name="num1">
        <select name="op">
            <option value="add">+</option>
            <option value="sub">-</option>
            <option value="mul">*</option>
            <option value="div">/</option>
        </select>
        <input type="number" name="num2">
        <input type="submit" value="Oblicz">
    </form>
</body>
</html>
<?php
    if (!isset($_GET["num1"]) || !isset($_GET["num2"])) {
        return;
    }
    $num1 = $_GET["num1"];
    $num2 = $_GET["num2"];
    $op = $_GET["op"];
    if (!is_numeric($num1) || !is_numeric($num2)) {
        echo "Nie podałeś liczb";
        return;
    }
    if ($op == "div" && $num2 == 0) {
        echo "Nie można dzielić przez 0";
        return;
    }
    switch ($op) {
        case "add":
            echo $num1 . "+" . $num2 . "=" . ($num1 + $num2);
            break;
        case "sub":
            echo $num1 . "-" . $num2 . "=" . ($num1 - $num2);
            break;
        case "mul":
            echo $num1 . "*" . $num2 . "=" . ($num1 * $num2);
            break;
        case "div":
            echo $num1 . "/" . $num2 . "=" . ($num1 / $num2);
            break;
        default:
            echo "Błędny operator";
            return;
    }
?>