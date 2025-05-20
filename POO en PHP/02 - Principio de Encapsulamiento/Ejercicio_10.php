<?php
class CuentaCorriente {
    private $saldo;
    private $limite;

    public function __construct($saldo, $limite) {
        $this->saldo = $saldo;
        $this->limite = $limite;
    }

    public function getSaldo() {
        return $this->saldo;
    }

    public function retirar($monto) {
        if ($monto <= $this->saldo + $this->limite) {
            $this->saldo -= $monto;
            return true;
        }
        return false;
    }
}

$cuenta = new CuentaCorriente(500, 200);
if ($cuenta->retirar(600)) {
    echo "Retiro exitoso. Saldo actual: " . $cuenta->getSaldo() . "\n";
} else {
    echo "Fondos insuficientes." . "\n";
}

$cuenta1 = new CuentaCorriente(300, 100);
if ($cuenta1->retirar(1500)) {
    echo "Retiro exitoso. Saldo actual: " . $cuenta1->getSaldo() . "\n";
} else {
    echo "Fondos insuficientes." . "\n";
}