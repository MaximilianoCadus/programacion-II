<?php
class Circulo {
    private $radio;

    public function __construct($radio) {
        $this->setRadio($radio);
    }

    public function getRadio() {
        return $this->radio;
    }

    public function setRadio($radio) {
        if ($radio > 0) {
            $this->radio = $radio;
        } else {
            throw new Exception("El radio debe ser un valor positivo.");
        }
    }
}

try {
    $circulo1 = new Circulo(5);
    echo "Radio válido: " . $circulo1->getRadio() . "\n";

    $circulo2 = new Circulo(-3);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}