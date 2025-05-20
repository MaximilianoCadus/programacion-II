<?php

class Empleado {
    private $sueldo;

    public function __construct($sueldoInicial) {
        $this->sueldo = $sueldoInicial;
    }

    public function getSueldo() {
        return $this->sueldo;
    }

    public function aumentarSueldo($porcentaje) {
        $this->sueldo += $this->sueldo * ($porcentaje / 100);
    }
}

$empleado = new Empleado(50000);
$empleado->aumentarSueldo(10);
echo $empleado->getSueldo();