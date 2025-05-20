<?php

class Libro {
    public $titulo;
    public $autor;

    public function __construct($titulo, $autor) {
        $this->titulo = $titulo;
        $this->autor = $autor;
    }

    public function mostrarInformacion() {
        echo "Título: " . $this->titulo . "\n";
        echo "Autor: " . $this->autor . "\n";
    }
}

$miLibro = new Libro("Cien años de soledad", "Gabriel García Márquez");

$miLibro->mostrarInformacion();