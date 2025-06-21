<?php
class GameCheckers extends Game {
    public function setup() {
        $this->set_state("pawns", " b b b bb b b b  b b b b                w w w w  w w w ww w w w ");
    }

    public function getPawn(x, y) {
        
    }
}