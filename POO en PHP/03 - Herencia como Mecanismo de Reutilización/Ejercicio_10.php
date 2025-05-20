<?php
abstract class Animal {
    public $nombre;

    public function __construct($nombre) {
        $this->nombre = $nombre;
    }

    public function moverse() {
        return "$this->nombre se está moviendo.";
    }

    abstract public function alimentarse();
}

class Pez extends Animal {
    public $tipoAgua;

    public function __construct($nombre, $tipoAgua) {
        parent::__construct($nombre);
        $this->tipoAgua = $tipoAgua;
    }

    public function moverse() {
        return "$this->nombre está nadando en agua $this->tipoAgua." . "\n";
    }

    public function alimentarse() {
        return "$this->nombre está comiendo plancton." . "\n";
    }
}

$pez = new Pez("Nemo", "salada");
echo $pez->moverse();
echo $pez->alimentarse();