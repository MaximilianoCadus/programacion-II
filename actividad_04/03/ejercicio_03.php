<?php

require __DIR__ . '/Proveedor/Herramientas/ayudante.php';

use Models\Ayudante as AyudaProveedor;

$ayudaProveedor = new AyudaProveedor();
$ayudaProveedor->ayudar();