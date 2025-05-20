<?php
class Producto {
    private $precio;

    public function __construct($precio) {
        $this->setPrecio($precio);
    }

    public function getPrecio() {
        return $this->precio;
    }

    public function setPrecio($precio) {
        if ($precio > 0) {
            $this->precio = $precio;
        } else {
            throw new Exception("El precio debe ser positivo.");
        }
    }
}

try {
    $producto1 = new Producto(100);
    echo "Precio del producto 1: " . $producto1->getPrecio() . "\n";

    $producto1->setPrecio(200);
    echo "Nuevo precio del producto 1: " . $producto1->getPrecio() . "\n";

    $producto2 = new Producto(-50);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}