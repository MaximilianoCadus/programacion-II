<?php
class Producto {
    public $nombre;
    public $precio;
    public $stock;

    public function __construct($nombre, $precio, $stock) {
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->stock = $stock;
    }

    public function valorInventario() {
        return $this->precio * $this->stock;
    }
}

$producto = new Producto("Laptop", 1500, 10);
echo $producto->valorInventario();