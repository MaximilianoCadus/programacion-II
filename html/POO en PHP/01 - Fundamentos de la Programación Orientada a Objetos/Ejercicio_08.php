<?php

class Circulo {
    public $radio;

    public function __construct($radio) {
        $this->radio = $radio;
    }

    public function calcularPerimetro() {
        return 2 * pi() * $this->radio;
    }
}

$circulo = new Circulo(5);
echo $circulo->calcularPerimetro();