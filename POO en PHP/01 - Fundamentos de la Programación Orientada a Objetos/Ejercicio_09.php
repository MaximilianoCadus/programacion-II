<?php
class Trabajador {
    public $nombre;
    public $cargo;
    public $salario;

    public function __construct($nombre, $cargo, $salario) {
        $this->nombre = $nombre;
        $this->cargo = $cargo;
        $this->salario = $salario;
    }

    public function informacion() {
        echo "Nombre: $this->nombre, Cargo: $this->cargo, Salario: $this->salario";
    }
}

$trabajador = new Trabajador("Maximiliano Cadús", "Desarrollador", 50000);
$trabajador->informacion();