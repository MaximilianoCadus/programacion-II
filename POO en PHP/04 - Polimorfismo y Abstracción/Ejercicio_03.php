<?php

interface Printable {
    public function imprimir();
}

class Documento implements Printable {
    public function imprimir() {
        return "Imprimiendo documento...";
    }
}

class Foto implements Printable {
    public function imprimir() {
        return "Imprimiendo foto...";
    }
}

$objetos = [new Documento(), new Foto(), new Documento()];

foreach ($objetos as $objeto) {
    echo $objeto->imprimir() . "\n";
}