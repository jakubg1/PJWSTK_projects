<!--
Code written as part of PJWSTK lessons
Task 2: Shop classes
-->

<?php
class Product {
    private $name;
    private $price;
    private $quantity;
    public function __construct($name, $price, $quantity) {
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    public function __toString() {
        return "Product: $this->name, Price: $this->price, Quantity: $this->quantity";
    }
}

class Cart {
    private $products;
    public function __construct() {
        $this->products = array();
    }

    public function addProduct(Product $product) {
        $this->products[] = $product;
    }

    public function removeProduct(Product $product) {
        $key = array_search($product, $this->products);
        if ($key !== false) {
            unset($this->products[$key]);
        }
    }

    public function getTotal() {
        $total = 0;
        foreach ($this->products as $product) {
            $total += $product->getPrice();
        }
        return $total;
    }

    public function __toString() {
        $s = "Products in cart:<br/>";
        foreach ($this->products as $product) {
            $s .= "Product: $product<br/>";
        }
        $total = $this->getTotal();
        $s .= "Total price: $total()<br/>";
        return $s;
    }
}
?>