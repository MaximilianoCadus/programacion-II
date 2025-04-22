<?php

abstract class Figura {
    abstract public function calcularArea();
}

class Triangulo extends Figura {
    private $base;
    private $altura;

    public function __construct($base, $altura) {
        $this->base = $base;
        $this->altura = $altura;
    }

    public function calcularArea() {
        return ($this->base * $this->altura) / 2;
    }
}

class Circulo extends Figura {
    private $radio;

    public function __construct($radio) {
        $this->radio = $radio;
    }

    public function calcularArea() {
        return pi() * pow($this->radio, 2);
    }
}

$figuras = [
    new Triangulo(10, 5),
    new Circulo(7)
];

foreach ($figuras as $figura) {
    echo $figura->calcularArea() . PHP_EOL;
}