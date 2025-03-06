<?php
echo "<h1>Zadanie 4</h1>";
$text = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a gallery of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.";
$atext = explode(" ", $text);
for ($i = sizeof($atext) - 1; $i >= 0; $i--) {
    $letters = str_split($atext[$i]);
    if (in_array(".", $letters) || in_array(",", $letters) || in_array("'", $letters)) {
        for ($j = $i; $j < sizeof($atext) - 1; $j++) {
            $atext[$j] = $atext[$j + 1];
        }
        array_pop($atext);
    }
}
foreach ($atext as $word) {
    if (!isset($key)) {
        $key = $word;
    } else {
        $assoc[$key] = $word;
        unset($key);
    }
}
foreach ($assoc as $key => $value) {
    echo $key . " " . $value . "<br>";
}
?>