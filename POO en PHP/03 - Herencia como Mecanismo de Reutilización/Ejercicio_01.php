<?php
abstract class Vehiculo {
    public $marca;

    public function __construct($marca) {
        $this->marca = $marca;
    }

    public function avanzar($kilometros) {
        return "El vehículo avanza $kilometros kilómetros.";
    }

    abstract public function desplazarse();
}
class Moto extends Vehiculo {
    public $cilindrada;

    public function __construct($marca, $cilindrada) {
        parent::__construct($marca);
        $this->cilindrada = $cilindrada;
    }

    public function avanzar($kilometros) {
        return "La moto avanza rápidamente $kilometros kilómetros.";
    }

    public function desplazarse() {
        return "La moto se está desplazando.";
    }
}

$moto = new Moto("Yamaha", 150);
echo $moto->avanzar(10);