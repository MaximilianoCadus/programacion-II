<?php

require __DIR__ . '/Configuraciones.php';

use Configuraciones\ConfiguracionApp;

$configuracion = new ConfiguracionApp();
$nombre = $configuracion->NOMBRE_APP(); 
echo $nombre;