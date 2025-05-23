<?php

namespace Vistas;

require __DIR__ . '/Contratos.php';

use Contratos\Renderable;

Class Vista 
{
    public function renderizar()
    {
        Renderable::renderizar();
        return "Renderizando vista";
    }
}
