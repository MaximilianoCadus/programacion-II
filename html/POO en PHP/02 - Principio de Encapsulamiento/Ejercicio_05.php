<?php

class Estudiante {
    private $calificaciones = [];

    public function __construct(array $calificaciones = []) {
        foreach ($calificaciones as $calificacion) {
            $this->agregarCalificacion($calificacion);
        }
    }

    public function agregarCalificacion($calificacion) {
        if ($calificacion >= 0 && $calificacion <= 10) {
            $this->calificaciones[] = $calificacion;
        }
    }

    public function getPromedio() {
        if (count($this->calificaciones) === 0) {
            return 0;
        }
        return array_sum($this->calificaciones) / count($this->calificaciones);
    }
}

$estudiante = new Estudiante([8, 9, 10]);
$estudiante->agregarCalificacion(7);
echo $estudiante->getPromedio();