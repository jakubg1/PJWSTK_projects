<?php
class Pawn {
    private $color;
    private $queen;

    public function __construct($state) {
        $this->set_state($state);
    }

    // Returns "b" or "w".
    public function get_color() {
        return $this->color;
    }

    public function set_color($color) {
        $this->color = $color;
    }

    // Returns a boolean.
    public function get_queen() {
        return $this->queen;
    }

    public function set_queen($queen) {
        $this->queen = $queen;
    }

    // Returns "b", "B", "w" or "W".
    public function get_state() {
        if ($this->queen) {
            return $this->color == "b" ? "B" : "W";
        } else {
            return $this->color;
        }
    }

    public function set_state($state) {
        $this->color = ($state == "b" || $state == "B") ? "b" : "w";
        $this->queen = $state == "B" || $state == "W";
    }
}