<?php

class Persona {
    public $nombre;
    public $edad;

    public function __construct($nombre, $edad) {
        $this->nombre = $nombre;
        $this->edad = $edad;
    }

    public function saludar() {
        return "Hola, mi nombre es $this->nombre y tengo $this->edad años.";
    }
}

class Profesor extends Persona {
    public $materia;

    public function __construct($nombre, $edad, $materia) {
        parent::__construct($nombre, $edad);
        $this->materia = $materia;
    }

    public function saludar() {
        return parent::saludar() . " Soy profesor de $this->materia.";
    }
}

$profesor = new Profesor("Bruse", 30, "Programación II"); 
echo $profesor->saludar();
