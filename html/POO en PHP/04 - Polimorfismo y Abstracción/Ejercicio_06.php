<?php

abstract class Trabajador {
    protected $salario;
    abstract public function calcularIngreso();
}

class Fijo extends Trabajador {
    protected $salario;

    public function __construct($salario) {
        $this->salario = $salario;
    }

    public function calcularIngreso() {
        return $this->salario;
    }
}

class Temporal extends Trabajador {
    private $horasTrabajadas;
    private $pagoPorHora;

    public function __construct($horasTrabajadas, $pagoPorHora) {
        $this->horasTrabajadas = $horasTrabajadas;
        $this->pagoPorHora = $pagoPorHora;
    }

    public function calcularIngreso() {
        return $this->horasTrabajadas * $this->pagoPorHora;
    }
}

$trabajadores = [
    new Fijo(3000),
    new Temporal(120, 15)
];

$ingresos = array_map(fn($trabajador) => $trabajador->calcularIngreso(), $trabajadores);
foreach ($ingresos as $index => $ingreso) {
    echo "El ingreso del trabajador " . ($index + 1) . " es: $" . $ingreso . PHP_EOL;
}