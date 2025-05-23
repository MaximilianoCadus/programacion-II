<?php

require __DIR__ . '/Vistas.php';

use Vistas\Vista;

$vista = new Vista();
echo $vista->renderizar();