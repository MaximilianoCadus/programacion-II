<?php
abstract class Animal {
    public $especie;
    public $nombre;

    public function __construct($nombre) {
        $this->nombre = $nombre;
    }

    public function emitirSonido() {
        echo "Sonido genérico";
    }

    abstract public function alimentarse();
}
class Gato extends Animal {
    public function emitirSonido() {
        echo "¡Miau!";
    }

    public function alimentarse() {
        echo "El gato se alimenta de pescado.";
    }
}

$gato = new Gato("Tom");
$gato->emitirSonido();