<?php
class Vehiculo {
    private $kilometros;

    public function __construct($kilometrosIniciales = 0) {
        $this->kilometros = $kilometrosIniciales;
    }

    public function getKilometros() {
        return $this->kilometros;
    }

    public function avanzar($kilometros) {
        if ($kilometros > 0) {
            $this->kilometros += $kilometros;
        }
    }
}

$miVehiculo = new Vehiculo();
$miVehiculo->avanzar(50);
echo $miVehiculo->getKilometros();