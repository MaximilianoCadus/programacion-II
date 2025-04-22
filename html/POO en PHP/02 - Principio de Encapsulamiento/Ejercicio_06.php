<?php

class Rectangulo {
    private $largo;
    private $ancho;

    public function __construct($largo, $ancho) {
        $this->setDimensiones($largo, $ancho);
    }

    public function getLargo() {
        return $this->largo;
    }

    public function getAncho() {
        return $this->ancho;
    }

    public function setDimensiones($largo, $ancho) {
        if ($largo > 0 && $ancho > 0) {
            $this->largo = $largo;
            $this->ancho = $ancho;
        } else {
            throw new Exception("Las dimensiones deben ser valores positivos.");
        }
    }
}

try {
    $rectangulo = new Rectangulo(10, 5);
    echo "Largo: " . $rectangulo->getLargo() . "\n";
    echo "Ancho: " . $rectangulo->getAncho() . "\n";
    $rectangulo->setDimensiones(15, 8);
    echo "Nuevas dimensiones - Largo: " . $rectangulo->getLargo() . ", Ancho: " . $rectangulo->getAncho();
} catch (Exception $e) {
    echo $e->getMessage();
}