<?php

class Cuenta {
    public $saldo;

    public function __construct($saldoInicial = 0) {
        $this->saldo = $saldoInicial;
    }

    public function ingresar($monto) {
        $this->saldo += $monto;
    }

    public function retirar($monto) {
        if ($monto <= $this->saldo) {
            $this->saldo -= $monto;
        }
    }

    public function obtenerSaldo() {
        return $this->saldo;
    }
}

$cuenta = new Cuenta(100);
$cuenta->ingresar(50);
$cuenta->retirar(30);
echo "Saldo final: " . $cuenta->obtenerSaldo();