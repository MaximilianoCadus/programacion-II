<?php

abstract class Animal {
    protected $nombre;

    abstract public function alimentarse();
}

class Leon extends Animal {
    protected $nombre;

    public function __construct($nombre) {
        $this->nombre = $nombre;
    }
    public function alimentarse() {
        return "El león llamado {$this->nombre} se alimenta de carne.";
    }
}
class Pajaro extends Animal {
    protected $nombre;

    public function __construct($nombre) {
        $this->nombre = $nombre;
    }

    public function alimentarse() {
        return "El pájaro llamado {$this->nombre} se alimenta de lombrices.";
    }
}

$animales = [new Leon("Simba"), new Pajaro("Tweety")];

foreach ($animales as $animal) {
    echo $animal->alimentarse() . "\n";
}