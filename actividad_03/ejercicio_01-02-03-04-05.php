<?php
$host = '172.16.203.164';
$dbname = 'actividad3';
$username = 'root';
$password = 'Itachi91218';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Conexión exitosa a la base de datos." . "\n";
} catch (PDOException $e) {
    echo "Error al conectar a la base de datos: " . $e->getMessage() . "\n";
}

$createTableQuery = "
    CREATE TABLE IF NOT EXISTS productos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(50) NOT NULL,
        precio DECIMAL(10,2) NOT NULL
    )
";
$pdo->exec($createTableQuery);

$insertQuery = "
    INSERT INTO productos (nombre, precio)
    SELECT * FROM (SELECT 'Producto A' AS nombre, 10.50 AS precio) AS tmp
    WHERE NOT EXISTS (
        SELECT nombre FROM productos WHERE nombre = 'Producto A'
    )
    UNION ALL
    SELECT * FROM (SELECT 'Producto B' AS nombre, 20.00 AS precio) AS tmp
    WHERE NOT EXISTS (
        SELECT nombre FROM productos WHERE nombre = 'Producto B'
    )
    UNION ALL
    SELECT * FROM (SELECT 'Producto C' AS nombre, 15.75 AS precio) AS tmp
    WHERE NOT EXISTS (
        SELECT nombre FROM productos WHERE nombre = 'Producto C'
    )
";
$pdo->exec($insertQuery);

$selectQuery = "SELECT * FROM productos ORDER BY id DESC";
$stmt = $pdo->query($selectQuery);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$nombreBuscado = 'Producto B';
$sql = "SELECT * FROM productos WHERE nombre = :nombre";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':nombre', $nombreBuscado, PDO::PARAM_STR);
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($productos as $producto) {
    echo "ID: " . $producto['id'] . " - Nombre: " . $producto['nombre'] . " - Precio: $" . $producto['precio'] . "\n";
}

$createUsersTableQuery = "
    CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(100) NOT NULL,
        estado VARCHAR(20) NOT NULL
    )
";
$pdo->exec($createUsersTableQuery);

$insertUsersQuery = "
    INSERT INTO usuarios (email, estado)
    SELECT * FROM (SELECT 'usuario1@example.com' AS email, 'activo' AS estado) AS tmp
    WHERE NOT EXISTS (
        SELECT email FROM usuarios WHERE email = 'usuario1@example.com'
    )
    UNION ALL
    SELECT * FROM (SELECT 'usuario2@example.com' AS email, 'inactivo' AS estado) AS tmp
    WHERE NOT EXISTS (
        SELECT email FROM usuarios WHERE email = 'usuario2@example.com'
    )
    UNION ALL
    SELECT * FROM (SELECT 'usuario3@example.com' AS email, 'activo' AS estado) AS tmp
    WHERE NOT EXISTS (
        SELECT email FROM usuarios WHERE email = 'usuario3@example.com'
    )
";
$pdo->exec($insertUsersQuery);

$nuevoEstado = 'inactivo'; 
$idUsuario = 1; 

try {
    $updateQuery = "UPDATE usuarios SET estado = :estado WHERE id = :id";
    $stmt = $pdo->prepare($updateQuery);
    $stmt->bindParam(':estado', $nuevoEstado, PDO::PARAM_STR);
    $stmt->bindParam(':id', $idUsuario, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "El estado del usuario con ID $idUsuario se actualizó a '$nuevoEstado'.\n";
    } else {
        echo "No se encontró un usuario con ID $idUsuario o el estado ya era '$nuevoEstado'.\n";
    }
} catch (PDOException $e) {
    echo "Error al actualizar el estado del usuario: " . $e->getMessage() . "\n";
}

$createCuentasTableQuery = "
    CREATE TABLE IF NOT EXISTS cuentas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        saldo DECIMAL(10,2) NOT NULL
    )
";
$pdo->exec($createCuentasTableQuery);

$insertCuentasQuery = "
    INSERT INTO cuentas (saldo)
    SELECT * FROM (SELECT 1000.00 AS saldo) AS tmp
    WHERE NOT EXISTS (SELECT id FROM cuentas WHERE id = 1)
    UNION ALL
    SELECT * FROM (SELECT 500.00 AS saldo) AS tmp
    WHERE NOT EXISTS (SELECT id FROM cuentas WHERE id = 2)
";
$pdo->exec($insertCuentasQuery);

$monto = 200.00; 
$idCuentaA = 1; 
$idCuentaB = 2; 

try {
    $pdo->beginTransaction();

    $restarQuery = "UPDATE cuentas SET saldo = saldo - :monto WHERE id = :id";
    $stmt = $pdo->prepare($restarQuery);
    $stmt->bindParam(':monto', $monto, PDO::PARAM_STR);
    $stmt->bindParam(':id', $idCuentaA, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        throw new Exception("La cuenta origen con ID $idCuentaA no existe o no tiene suficiente saldo.");
    }

    $sumarQuery = "UPDATE cuentas SET saldo = saldo + :monto WHERE id = :id";
    $stmt = $pdo->prepare($sumarQuery);
    $stmt->bindParam(':monto', $monto, PDO::PARAM_STR);
    $stmt->bindParam(':id', $idCuentaB, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        throw new Exception("La cuenta destino con ID $idCuentaB no existe.");
    }

    $pdo->commit();
    echo "Transferencia completada exitosamente.\n";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Error en la transferencia: " . $e->getMessage() . "\n";
}