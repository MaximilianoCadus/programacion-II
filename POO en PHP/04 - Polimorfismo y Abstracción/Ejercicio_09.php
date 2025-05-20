<?php

interface Calculable {
    public function calcularPerimetro();
}

class Cuadrado implements Calculable {
    private $lado;

    public function __construct($lado) {
        $this->lado = $lado;
    }

    public function calcularPerimetro() {
        return 4 * $this->lado;
    }
}

class Circulo implements Calculable {
    private $radio;

    public function __construct($radio) {
        $this->radio = $radio;
    }

    public function calcularPerimetro() {
        return 2 * pi() * $this->radio;
    }
}

$figuras = [
    new Cuadrado(5),
    new Circulo(3),
    new Cuadrado(7),
    new Circulo(4)
];

foreach ($figuras as $figura) {
    echo $figura->calcularPerimetro() . 2 . "\n";
}