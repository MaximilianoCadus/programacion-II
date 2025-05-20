<?php
class Persona {
    public $nombre;
    public $edad;

    public function __construct($nombre, $edad) {
        $this->nombre = $nombre;
        $this->edad = $edad;
    }

    public function esAdulto() {
        return $this->edad >= 18;
    }
}

$persona = new Persona("Maxi", 23);
echo $persona->esAdulto() ? "Es adulto" . "\n": "No es adulto" . "\n";

$persona2 = new Persona("Juan", 16);
echo $persona2->esAdulto() ? "Es adulto" . "\n": "No es adulto" . "\n";