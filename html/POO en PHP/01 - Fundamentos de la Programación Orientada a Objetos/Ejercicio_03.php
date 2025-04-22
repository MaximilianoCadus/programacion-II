<?php
class Estudiante {
    public $nombre;
    public $edad;
    public $matricula;

    public function __construct($nombre, $edad, $matricula) {
        $this->nombre = $nombre;
        $this->edad = $edad;
        $this->matricula = $matricula;
    }

    public function mostrarDatos() {
        echo "Nombre: " . $this->nombre . "\n";
        echo "Edad: " . $this->edad . "\n";
        echo "Matrícula: " . $this->matricula . "\n";
    }
}

$estudiante = new Estudiante("Maximiliano Cadús", 23, "A12345");

$estudiante->mostrarDatos();