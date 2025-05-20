<?php

abstract class Instrumento {
    abstract public function sonar();
    abstract public function tocar();
}

class Piano extends Instrumento {
    public function sonar() {
        echo "El piano está sonando." . "\n";
    }

    public function tocar() {
        echo "El piano está siendo tocado." . "\n";
    }
}

$piano = new Piano();
$piano->sonar();
$piano->tocar();