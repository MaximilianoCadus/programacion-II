<?php

abstract class Instrumento {
    abstract public function tocar();
    abstract public function sonar();
}
class Violin extends Instrumento {
public function tocar() {
    return "Tocando el violín";
}

public function sonar() {
    return "Sonando el violín";
}
}
class Bateria extends Instrumento {
    public function tocar() {
        return "Tocando la batería";
    }

    public function sonar() {
        return "Sonando la batería";
    }
}

$instrumentos = [
    new Violin(),
    new Bateria()
];

foreach ($instrumentos as $instrumento) {
    echo $instrumento->tocar() . "\n";
}