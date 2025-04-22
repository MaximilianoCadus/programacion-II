<?php

interface Reproducible {
    public function reproducir();
}

class Pelicula implements Reproducible {
    private $titulo;

    public function __construct($titulo) {
        $this->titulo = $titulo;
    }

    public function reproducir() {
        echo "Reproduciendo película: {$this->titulo}\n";
    }
}

class Podcast implements Reproducible {
    private $nombre;

    public function __construct($nombre) {
        $this->nombre = $nombre;
    }

    public function reproducir() {
        echo "Reproduciendo podcast: {$this->nombre}\n";
    }
}

$elementos = [
    new Pelicula("Inception"),
    new Podcast("Aprendiendo PHP"),
    new Pelicula("Matrix"),
    new Podcast("Tecnología Hoy")
];

foreach ($elementos as $elemento) {
    $elemento->reproducir();
}