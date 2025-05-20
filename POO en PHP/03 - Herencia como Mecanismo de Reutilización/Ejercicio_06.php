<?php
class Cuenta {
    protected $saldo;

    public function __construct($saldoInicial) {
        $this->saldo = $saldoInicial;
    }

    public function depositar($monto) {
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

class CuentaPremium extends Cuenta {
    private $bonificacion;

    public function __construct($saldoInicial, $bonificacion) {
        parent::__construct($saldoInicial);
        $this->bonificacion = $bonificacion;
    }

    public function aplicarBonificacion() {
        $this->saldo += $this->bonificacion;
    }
}

$cuenta = new CuentaPremium(1000, 200);
$cuenta->aplicarBonificacion();
echo $cuenta->obtenerSaldo();