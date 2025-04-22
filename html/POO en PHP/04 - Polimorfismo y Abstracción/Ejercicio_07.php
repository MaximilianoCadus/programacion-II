<?php

interface Comunicable {
    public function enviarMensaje($mensaje);
}

class Correo implements Comunicable {
    public function enviarMensaje($mensaje) {
        return "Correo enviado: " . $mensaje;
    }
}

class Texto implements Comunicable {
    public function enviarMensaje($mensaje) {
        return "Texto enviado: " . $mensaje;
    }
}

$comunicaciones = [
    new Correo(),
    new Texto()
];

foreach ($comunicaciones as $comunicacion) {
    echo $comunicacion->enviarMensaje("Hola") . "\n";
}