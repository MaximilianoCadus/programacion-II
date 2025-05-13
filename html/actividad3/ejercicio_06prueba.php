<?php

require_once 'ejercicio_06conexion.php';

$host = '192.168.0.77';
$dbname = 'actividad3';
$username = 'root';
$password = 'Itachi91218';

$db = new Database($host, $dbname, $username, $password);

$createUsersTableQuery = "
    CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(100) NOT NULL,
        estado VARCHAR(20) NOT NULL
    )
";
$db->getPdo()->exec($createUsersTableQuery);

$email = 'nuevo_usuario@example.com';
$estado = 'activo';
$userId = $db->createUser($email, $estado);
if ($userId) {
    echo "Usuario creado con ID: $userId\n";
}

$user = $db->getUserById($userId);
if ($user) {
    echo "Usuario consultado: ID: {$user['id']}, Email: {$user['email']}, Estado: {$user['estado']}\n";
}

$nuevoEstado = 'inactivo';
if ($db->updateUserState($userId, $nuevoEstado)) {
    echo "Estado del usuario con ID $userId actualizado a '$nuevoEstado'.\n";
} else {
    echo "No se pudo actualizar el estado del usuario con ID $userId.\n";
}