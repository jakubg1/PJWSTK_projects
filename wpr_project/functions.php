<?php
function html_start() {
    echo '<html>';
    echo '<head>';
    echo '<title>Stuff is cooking here</title>';
    echo '<meta charset="UTF-8">';
    echo '<link rel="stylesheet" href="style.css">';
    echo '</head>';
    echo '<body>';
    echo '<div id="main">';
}

function html_end() {
    echo '</div>';
    echo '</body>';
    echo '</html>';
}