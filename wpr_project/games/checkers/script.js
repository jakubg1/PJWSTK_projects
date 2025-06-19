function onLoad() {
    generateBoard();
}

function generateBoard() {
    for (let i = 0; i < 64; i++) {
        let board = $("#board");
        board.append("<div>");
        let tile = board.find("div").last();
        tile.append("<div class='inner'>");
        let white = (i % 8 + Math.floor(i / 8)) % 2 == 0;
        tile.addClass(white ? "white" : "black");
    }
}

$(document).ready(onLoad);