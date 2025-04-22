<?php

abstract class Vehiculo {
    protected $velocidad = 0;

    public function acelerar() {
        $this->velocidad += 5;
    }

    public function getVelocidad() {
        return $this->velocidad;
    }

    abstract public function desplazarse();
}
class Camion extends Vehiculo {
    public function acelerar() {
        $this->velocidad += 10;
    }

    public function desplazarse() {
        echo "El camión se está desplazando.";
    }
}

$camion = new Camion();
$camion->acelerar();
echo $camion->getVelocidad();