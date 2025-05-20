<?php

class Rectangulo {
    private $largo;
    private $ancho;

    public function __construct($largo, $ancho) {
        $this->largo = $largo;
        $this->ancho = $ancho;
    }

    public function calcularArea() {
        return $this->largo * $this->ancho;
    }
}

$miRectangulo = new Rectangulo(10, 5);

echo "El área del rectángulo es: " . $miRectangulo->calcularArea();