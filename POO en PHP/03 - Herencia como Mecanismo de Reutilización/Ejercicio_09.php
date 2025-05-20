<?php

class Empleado {
    protected $nombre;
    protected $salario;

    public function __construct($nombre, $salario) {
        $this->nombre = $nombre;
        $this->salario = $salario;
    }

    public function calcularPago() {
        return $this->salario;
    }
}

class Freelancer extends Empleado {
    private $horas;

    public function __construct($nombre, $salarioPorHora, $horas) {
        parent::__construct($nombre, $salarioPorHora);
        $this->horas = $horas;
    }

    public function calcularPago() {
        return $this->salario * $this->horas;
    }
}

$empleado = new Empleado("Juan", 3000);
echo "Pago Empleado: " . $empleado->calcularPago() . "\n";

$freelancer = new Freelancer("Ana", 50, 40);
echo "Pago Freelancer: " . $freelancer->calcularPago() . "\n";