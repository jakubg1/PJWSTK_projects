let pawnTable = [];

function onLoad() {
    generatePawnTable();
    generateBoard();
}

function generatePawnTable() {
    pawnTable = [];
    for (let x = 0; x < 8; x++) {
        pawnTable[x] = [];
        for (let y = 0; y < 8; y++) {
            pawnTable[x][y] = null;
        }
    }
}

function generateBoard() {
    // Generate tiles.
    let tiles = $("#tiles");
    for (let i = 0; i < 64; i++) {
        tiles.append("<div class='tile'>");
        let tile = tiles.find("div").last();
        let white = (i % 8 + Math.floor(i / 8)) % 2 == 0;
        tile.addClass(white ? "white" : "black");
        tile.append("<div class='inner'>");
    }
    // Place pawns.
    for (let x = 0; x < 8; x++) {
        for (let y = 0; y < 8; y++) {
            if ((x + y) % 2 == 0)
                continue;
            if (y <= 2)
                addPawn(x, y, "black");
            if (y >= 5)
                addPawn(x, y, "white");
        }
    }
}

function addPawn(x, y, color) {
    // Do not overwrite pawns.
    if (pawnTable[x][y] != null)
        return;
    let pawns = $("#pawns");
    pawns.append("<div class='pawn'>");
    let pawn = pawns.find(".pawn").last();
    pawn.append("<div class='layer1'>");
    pawn.append("<div class='layer2'>");
    pawn.append("<div class='layer3'>");
    pawn.append("<div class='layer4'>");
    pawn.addClass(color);
    pawn.css({left: x * 12.5 + "vh", top: y * 12.5 + "vh"});
    pawnTable[x][y] = pawn;
}

function movePawn(x1, y1, x2, y2) {
    let pawn = pawnTable[x1][y1];
    // Cannot move air or move something on top of something else.
    if (pawn == null || pawnTable[x2][y2] != null)
        return;
    // Animate movement.
    pawn.css({"z-index": 3});
    pawn.animate({left: x2 * 12.5 + "vh", top: y2 * 12.5 + "vh"}, 500, "swing", () => {pawn.css({"z-index": "auto"})});
    // Update the pawn table.
    pawnTable[x2][y2] = pawn;
    pawnTable[x1][y1] = null;
}

function removePawn(x, y) {
    let pawn = pawnTable[x][y];
    // Cannot remove air.
    if (pawn == null)
        return;
    // Animate movement.
    pawn.css({"z-index": 2});
    pawn.animate({left: "300vh", top: "43.75vh"}, 1500, "swing", () => {pawn.remove()});
    // Update the pawn table.
    pawnTable[x][y] = null;
}

$(document).ready(onLoad);