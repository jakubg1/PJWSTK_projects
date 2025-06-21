<?php
class Pawn {
    private $color;
    private $queen;

    // Returns "b" or "w".
    public getColor() {
        return $color;
    }

    public setColor($color) {
        $this->color = $color;
    }

    // Returns a boolean.
    public getQueen() {
        return $queen;
    }

    public setQueen($queen) {
        $this->queen = $queen;
    }

    // Returns "b", "B", "w" or "W".
    public getState() {
        if ($queen) {
            return $color == "b" ? "B" : "W";
        } else {
            return $color;
        }
    }

    public setState($state) {
        $this->color = ($state == "b" || $state == "B") ? "b" : "w";
        $this->queen = $state == "B" || $state == "W";
    }
}