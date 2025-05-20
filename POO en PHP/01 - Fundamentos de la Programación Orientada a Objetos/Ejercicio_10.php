<?php

class Triangulo {
    public $base;
    public $altura;

    public function __construct($base, $altura) {
        $this->base = $base;
        $this->altura = $altura;
    }

    public function area() {
        return ($this->base * $this->altura) / 2;
    }
}

$triangulo = new Triangulo(10, 5);
echo $triangulo->area();