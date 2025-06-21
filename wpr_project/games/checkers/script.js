let tileTable = [];
let pawnTable = [];
let selectedPawn = null;
let turn = "white"; // null - board locked, "white" - white, "black" - black

function onLoad() {
    tileTable = generate2DArray(8, 8);
    pawnTable = generate2DArray(8, 8);
    generateBoard();
}

function generate2DArray(w, h) {
    arr = [];
    for (let x = 0; x < w; x++) {
        arr[x] = [];
        for (let y = 0; y < h; y++) {
            arr[x][y] = null;
        }
    }
    return arr;
}

// Game control

function onTileClicked(x, y) {
    // If we've got a pawn selected already, check if we can make a move.
    if (selectedPawn != null) {
        let move = getMove(selectedPawn.x, selectedPawn.y, x, y, selectedPawn.locked);
        if (move != null) {
            unselectAllTiles();
            movePawn(selectedPawn.x, selectedPawn.y, move.x, move.y);
            let canContinue = false;
            if (move.killer) {
                removePawn(move.kx, move.ky);
                // Check if the player should continue (made a killer move and can make another one).
                let nextMoves = getValidMoves(move.x, move.y);
                for (let i = 0; i < nextMoves.length; i++) {
                    if (nextMoves[i].killer) {
                        canContinue = true;
                        break;
                    }
                }
            }
            if (canContinue) {
                selectedPawn = {x: move.x, y: move.y, locked: true};
                highlightValidMoves(move.x, move.y, true);
            } else {
                // If the pawn ends up at the last row, it gets promoted.
                tryPromotePawn(x, y);
                unselectPawn(x, y);
                turn = turn == "white" ? "black" : "white";
            }
            onMove(move, canContinue);
            return;
        }
        // Still exit if the selected pawn is locked.
        if (selectedPawn.locked)
            return;
    }
    // Select another pawn if the move cannot be made.
    selectPawn(x, y);
    // Highlight all valid moves.
    unselectAllTiles();
    if (selectedPawn != null)
        highlightValidMoves(x, y);
}

function isInBounds(x, y) {
    return x >= 0 && x < 8 && y >= 0 && y < 8;
}

// Counts pawns on a diagonal, dx and dy must be -1 or 1
// The starting tile is not counted.
function countPawns(x, y, dx, dy, length) {
    let result = {white: [], black: []};
    for (let i = 1; i <= length; i++) {
        let tx = x + i * dx;
        let ty = y + i * dy;
        // Check if we went off the board.
        if (!isInBounds(tx, ty))
            break;
        let pawn = pawnTable[tx][ty];
        if (pawn != null) {
            if (pawn.hasClass("white"))
                result.white.push({x: tx, y: ty});
            else
                result.black.push({x: tx, y: ty});
        }
    }
    return result;
}

function getValidMoves(x, y) {
    let pawn = pawnTable[x][y];
    // Cannot move air.
    if (pawn == null)
        return;
    // Regular pawns can move up (white) or down (black), one up and one to the left or right.
    let dy = pawn.hasClass("white") ? -1 : 1;
    let moves = [];
    let directions = [{x: -1, y: -1}, {x: 1, y: -1}, {x: -1, y: 1}, {x: 1, y: 1}];
    for (let i = 0; i < directions.length; i++) {
        let dir = directions[i];
        // Regular pawns can move forwards one or two tiles, backwards only two tiles (when killing).
        let minLength = (dir.y == dy || pawn.hasClass("queen")) ? 1 : 2;
        let maxLength = pawn.hasClass("queen") ? 8 : 2;
        for (let l = minLength; l <= maxLength; l++) {
            let fx = x + dir.x * l;
            let fy = y + dir.y * l;
            // If we are going out of bounds or the target tile is occupied, do not proceed.
            if (!isInBounds(fx, fy) || pawnTable[fx][fy] != null)
                continue;
            let counts = countPawns(x, y, dir.x, dir.y, l);
            let enemies = pawn.hasClass("white") ? counts.black : counts.white;
            let allies = pawn.hasClass("white") ? counts.white : counts.black;
            // We cannot jump over allies, and we can jump over (kill) at most one enemy.
            if (allies.length > 0 || enemies.length > 1)
                continue;
            // If we are a regular pawn, we cannot move two tiles without killing an enemy.
            if (!pawn.hasClass("queen") && enemies.length == 0 && l == 2)
                continue;
            // Add the move. If we are hopping over one enemy, make a killer move. Otherwise, a regular one.
            if (enemies.length == 0)
                moves.push({killer: false, sx: x, sy: y, x: fx, y: fy});
            else
                moves.push({killer: true, sx: x, sy: y, x: fx, y: fy, kx: enemies[0].x, ky: enemies[0].y});
        }
    }
    return moves;
}

function getMove(x1, y1, x2, y2, killerOnly) {
    let moves = getValidMoves(x1, y1);
    for (let i = 0; i < moves.length; i++) {
        let move = moves[i];
        if (killerOnly && !move.killer)
            continue;
        if (move.x == x2 && move.y == y2)
            return moves[i];
    }
    return null;
}

function highlightValidMoves(x, y, killerOnly) {
    let moves = getValidMoves(x, y);
    for (let i = 0; i < moves.length; i++) {
        let move = moves[i];
        if (killerOnly && !move.killer)
            continue;
        selectTile(move.x, move.y);
    }
}

// HTML DOM management and board data management

function generateBoard() {
    generateTiles();
    // Pawn generation is done by loading the game state from the server.
    //generatePawns();
}

function generateTiles() {
    for (let y = 0; y < 8; y++) {
        for (let x = 0; x < 8; x++) {
            addTile(x, y);
        }
    }
}

function addTile(x, y) {
    let tiles = $("#tiles");
    tiles.append("<div class='tile'>");
    let tile = tiles.find("div").last();
    let white = (x + y) % 2 == 0;
    tile.addClass(white ? "white" : "black");
    tile.on("click", (e) => onTileClicked(x, y));
    tile.append("<div class='inner'>");
    tile.append("<div class='select'>");
    // Update the tile table.
    tileTable[x][y] = tile;
}

function selectTile(x, y) {
    let tile = tileTable[x][y];
    tile.addClass("selected");
}

function unselectTile(x, y) {
    let tile = tileTable[x][y];
    tile.removeClass("selected");
}

function unselectAllTiles() {
    for (let x = 0; x < 8; x++) {
        for (let y = 0; y < 8; y++) {
            unselectTile(x, y);
        }
    }
}

function generatePawns() {
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

function addPawn(x, y, color, queen) {
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
    if (queen)
        pawn.addClass("queen");
    // Update the click callback.
    pawn.off("click");
    pawn.on("click", (e) => onTileClicked(x, y));
    // Update the position.
    pawn.css({left: x * 12.5 + "vh", top: y * 12.5 + "vh"});
    // Update the pawn table.
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
    pawn.off("click");
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

function tryPromotePawn(x, y) {
    let pawn = pawnTable[x][y];
    // Cannot promote air.
    if (pawn == null)
        return;
    let lastRow = pawn.hasClass("white") ? 0 : 7;
    // Cannot promote a pawn which is not at the last row.
    if (y != lastRow)
        return;
    // Proceed with promotion.
    pawn.addClass("queen");
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
    selectedPawn = {x: x, y: y, locked: false};
}

function unselectPawn(x, y) {
    let pawn = pawnTable[x][y];
    // Cannot unselect air.
    if (pawn == null)
        return;
    pawn.removeClass("selected");
    selectedPawn = null;
}

// Callbacks
function onMove(move, canContinue) {
    window.top.postMessage({"type": "move", "move": move, "canContinue": canContinue});
}

window.addEventListener("message",
    function(e) {
        if (e.data.type == "setPawns") {
            let pawns = e.data.pawns;
            for (let x = 0; x < 8; x++) {
                for (let y = 0; y < 8; y++) {
                    let pawn = pawns[y * 8 + x];
                    if (pawn != " ") {
                        let color = (pawn == "w" || pawn == "W") ? "white" : "black";
                        let queen = pawn == "W" || pawn == "B";
                        addPawn(x, y, color, queen);
                    }
                }
            }
        }
    }
)

$(document).ready(onLoad);