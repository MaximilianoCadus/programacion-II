<?php
class Producto {
    protected $nombre;
    protected $precio;

    public function __construct($nombre, $precio) {
        $this->nombre = $nombre;
        $this->precio = $precio;
    }

    public function detalle() {
        return "Producto: $this->nombre, Precio: $this->precio";
    }
}

class ProductoOferta extends Producto {
    private $descuento;

    public function __construct($nombre, $precio, $descuento) {
        parent::__construct($nombre, $precio);
        $this->descuento = $descuento;
    }

    public function detalle() {
        $precioConDescuento = $this->precio - ($this->precio * $this->descuento / 100);
        return "Producto: $this->nombre, Precio Original: $this->precio, Precio con Descuento: $precioConDescuento";
    }
}

$producto = new ProductoOferta("Laptop", 1000, 20);
echo $producto->detalle();