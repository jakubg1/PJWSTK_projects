<!--
Code written as part of PJWSTK lessons
Task 1: Car class
-->

<?php
class NewCar {
    public $model;
    public $price; // EUR
    public $exchangeRate; // EUR -> PLN

    public function __construct($model, $price, $exchangeRate) {
        $this->model = $model;
        $this->price = $price;
        $this->exchangeRate = $exchangeRate;
    }

    public function calculatePrice() {
        return $this->price * $this->exchangeRate;
    }
}

class CarWithAdditions extends NewCar {
    public $alarmPrice;
    public $radioPrice;
    public $acPrice;

    public function __construct($model, $price, $exchangeRate, $alarmPrice, $radioPrice, $acPrice) {
        parent::__construct($model, $price, $exchangeRate);
        $this->alarmPrice = $alarmPrice;
        $this->radioPrice = $radioPrice;
        $this->acPrice = $acPrice;
    }

    public function calculatePrice() {
        return ($this->price + $this->alarmPrice + $this->radioPrice + $this->acPrice) * $this->exchangeRate;
    }
}

class Insurance extends CarWithAdditions {
    public $percentageValue;
    public $years;

    public function __construct($model, $price, $exchangeRate, $alarmPrice, $radioPrice, $acPrice, $percentageValue, $years) {
        parent::__construct($model, $price, $exchangeRate, $alarmPrice, $radioPrice, $acPrice);
        $this->percentageValue = $percentageValue;
        $this->years = $years;
    }

    public function calculatePrice() {
        return $this->percentageValue * (parent::calculatePrice() * ((100 - $this->years) / 100));
    }
}
?>