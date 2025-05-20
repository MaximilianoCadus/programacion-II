<?php
class Auto {
    public $marca;
    public $modelo;
    public $color;

    public function __construct($marca, $modelo, $color) {
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->color = $color;
    }

    public function detalles() {
        return "El auto es un $this->marca $this->modelo de color $this->color.";
    }
}

$coche = new Auto("Toyota", "Corolla", "Rojo");
echo $coche->detalles();