<?php

namespace Controladores;

require __DIR__ . '/Modelos.php';

use Modelos\Usuario;

class ControladorUsuario
{
    public function mostrarNombre()
    {
        $usuario = new Usuario();
        $nombre = $usuario->obtenerNombre();
        return "El nombre del usuario es: " . $nombre;
    }
}