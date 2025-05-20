<?php

interface Nadador {
    public function nadar();
}

class Pez implements Nadador {
    private $nombre;
    private $tipo;

    public function __construct($nombre, $tipo) {
        $this->nombre = $nombre;
        $this->tipo = $tipo;
    }

    public function nadar() {
        echo "El pez $this->nombre de tipo $this->tipo está nadando.\n";
    }
}

class Persona implements Nadador {
    private $nombre;
    private $edad;

    public function __construct($nombre, $edad) {
        $this->nombre = $nombre;
        $this->edad = $edad;
    }

    public function nadar() {
        echo "La persona $this->nombre de $this->edad años nada en la pileta.\n";
    }
}


$nadadores = [new Pez("Nemo", "pez payaso"), new Persona("Bruse", 30)];

foreach ($nadadores as $nadador) {
    $nadador->nadar();
}