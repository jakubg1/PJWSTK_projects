<?php
class GameCheckers extends Game {
    public function setup() {
        $this->set_state("pawns", " b b b bb b b b  b b b b                w w w w  w w w ww w w w ");
        $this->set_state("turn", 1);
    }

    public function get_pawn($x, $y) {
        $n = $y * 8 + $x;
        $state = substr($this->get_state("pawns"), $n, 1);
        if ($state == " ")
            return null;
        return new Pawn($state);
    }

    public function set_pawn($x, $y, $pawn = null) {
        $n = $y * 8 + $x;
        $state = $pawn != null ? $pawn->get_state() : " ";
        $pawns = $this->get_state("pawns");
        $pawns = substr_replace($pawns, $state, $n, 1);
        $this->set_state("pawns", $pawns);
    }

    public function move_pawn($sx, $sy, $x, $y) {
        $pawn = $this->get_pawn($sx, $sy);
        // Prevent a move from happening when there's nothing to move or something would get overwritten.
        if (!$pawn || $this->get_pawn($x, $y))
            return;
        $this->set_pawn($x, $y, $pawn);
        $this->set_pawn($sx, $sy);
    }

    public function try_promote_pawn($x, $y) {
        $pawn = $this->get_pawn($x, $y);
        // Cannot promote air.
        if (!$pawn)
            return;
        $can_promote = $pawn->get_color() == "b" ? $y == 7 : $y == 0;
        if ($can_promote) {
            $pawn->set_queen(true);
            $this->set_pawn($x, $y, $pawn);
        }
    }

    public function next_turn() {
        $turn = $this->get_state("turn");
        // 1 - white, 2 - black
        $this->set_state("turn", $turn == 1 ? 2 : 1);
    }

    public function debug_print_state() {
        $pawns = $this->get_state("pawns");
        for ($i = 0; $i < 8; $i++) {
            echo substr($pawns, $i * 8, 8) . "\n";
        }
    }
}