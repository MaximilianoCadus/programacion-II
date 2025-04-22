<?php
class Usuario {
    private $edad;

    public function __construct($edad) {
        $this->setEdad($edad);
    }

    public function getEdad() {
        return $this->edad;
    }

    public function setEdad($edad) {
        if ($edad > 0) {
            $this->edad = $edad;
        }
    }
}

$usuario = new Usuario(25);
echo $usuario->getEdad() . "\n";

$usuario->setEdad(30);
echo $usuario->getEdad() . "\n"; 