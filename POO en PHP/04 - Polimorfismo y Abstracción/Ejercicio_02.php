<?php

abstract class Vehiculo {
    abstract public function desplazarse();
}

class Bicicleta extends Vehiculo {
    public function desplazarse() {
        return "La bicicleta se desplaza pedaleando.";
    }
}

class Avion extends Vehiculo {
    public function desplazarse() {
        return "El avión se desplaza volando.";
    }
}

$vehiculos = [
    new Bicicleta(),
    new Avion()
];

foreach ($vehiculos as $vehiculo) {
    echo $vehiculo->desplazarse() . "\n";
}