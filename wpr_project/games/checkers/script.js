let pawnTable = [];
let selectedPawn = null;
let turn = "white"; // null - board locked, "white" - white, "black" - black

function onLoad() {
    generatePawnTable();
    generateBoard();
}

function onTileClicked(x, y) {
    // If we've got a pawn selected already, check if we can make a move.
    if (selectedPawn != null) {
        let move = getMove(selectedPawn.x, selectedPawn.y, x, y);
        console.log(move);
        if (move != null) {
            movePawn(selectedPawn.x, selectedPawn.y, move.x, move.y);
            unselectPawn(x, y);
            if (move.killer)
                removePawn(move.kx, move.ky);
            turn = turn == "white" ? "black" : "white";
            return;
        }
    }
    // Select another pawn if the move cannot be made.
    selectPawn(x, y);
    //console.log(getValidMoves(x, y));
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

// Game control

function isInBounds(x, y) {
    return x >= 0 && x < 8 && y >= 0 && y < 8;
}

function getValidMoves(x, y) {
    let pawn = pawnTable[x][y];
    // Cannot move air.
    if (pawn == null)
        return;
    // Regular pawns can move up (white) or down (black), one up and one to the left or right.
    let dy = pawn.hasClass("white") ? -1 : 1;
    let candidates = [-1, 1];
    let moves = [];
    for (let i = 0; i < candidates.length; i++) {
        let move = {killer: false, x: x + candidates[i], y: y + dy};
        // Cannot move off the board or on top of another pawn.
        if (isInBounds(move.x, move.y)) {
            let nextPawn = pawnTable[move.x][move.y];
            if (nextPawn == null)
                // We can make a regular move.
                moves.push(move);
            else {
                // If there is another pawn, check if it's one of the opposite team.
                if (nextPawn.hasClass("white") != pawn.hasClass("white")) {
                    // Try a killer move.
                    move = {killer: true, x: x + 2 * candidates[i], y: y + 2 * dy, kx: move.x, ky: move.y};
                    if (isInBounds(move.x, move.y) && pawnTable[move.x][move.y] == null)
                        moves.push(move);
                }
            }
        }
    }
    return moves;
}

function getMove(x1, y1, x2, y2) {
    let moves = getValidMoves(x1, y1);
    for (let i = 0; i < moves.length; i++) {
        if (moves[i].x == x2 && moves[i].y == y2)
            return moves[i];
    }
    return null;
}

// HTML DOM management and board data management

function generateBoard() {
    // Generate tiles.
    let tiles = $("#tiles");
    for (let y = 0; y < 8; y++) {
        for (let x = 0; x < 8; x++) {
            tiles.append("<div class='tile'>");
            let tile = tiles.find("div").last();
            let white = (x + y) % 2 == 0;
            tile.addClass(white ? "white" : "black");
            tile.on("click", (e) => onTileClicked(x, y));
            tile.append("<div class='inner'>");
        }
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
    pawn.append("<div class='layer0'>");
    pawn.append("<div class='layer1'>");
    pawn.append("<div class='layer2'>");
    pawn.append("<div class='layer3'>");
    pawn.append("<div class='layer4'>");
    pawn.addClass(color);
    pawn.on("click", (e) => onTileClicked(x, y));
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
    // Update the click callback.
    pawn.on("click", (e) => onTileClicked(x2, y2));
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
    // Clicking this pawn should do nothing from now on.
    pawn.off("click");
    // Update the pawn table.
    pawnTable[x][y] = null;
}

function selectPawn(x, y) {
    // Unselect the previous pawn.
    if (selectedPawn != null)
        unselectPawn(selectedPawn.x, selectedPawn.y);
    let pawn = pawnTable[x][y];
    // Cannot select air or something which goes against the current turn.
    if (pawn == null || !pawn.hasClass(turn))
        return;
    pawn.addClass("selected");
    selectedPawn = {x: x, y: y};
}

function unselectPawn(x, y) {
    let pawn = pawnTable[x][y];
    // Cannot unselect air.
    if (pawn == null)
        return;
    pawn.removeClass("selected");
    selectedPawn = null;
}

$(document).ready(onLoad);