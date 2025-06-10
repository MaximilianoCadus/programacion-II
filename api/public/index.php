<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require_once 'db_connection.php';

$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$host = '172.16.203.164';
$dbname = 'api';
$username = 'root';
$password = 'Itachi91218';

$db = new Database($host, $dbname, $username, $password);

$app->get('/userByUUID/{UUID}', function (Request $request, Response $response, $args) use ($db) {

    $UUID = $args['UUID'];

    if (!$UUID) {
        $response->getBody()->write(json_encode(['error' => 'UUID no proporcionado']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
    }

    $user = $db->getUserByUUID($UUID);

    if ($user) {
        $response->getBody()->write(json_encode($user));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
    
     $response->getBody()->write(json_encode(['error' => 'Usuario no encontrado']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
});

$app->post('/user', function (Request $request, Response $response, $args) use ($db) {

    $user = $request->getParsedBody();

    if (empty($user)) {
        $response->getBody()->write(json_encode(['error' => 'Usuario inválido', 'User' => $user]));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
    }
    
    $UUID = $user['UUID'] ?? null;
    $firstName = $user['firstName'] ?? null;
    $lastName = $user['lastName'] ?? null;

    if (!$UUID || !$firstName || !$lastName) {
        $response->getBody()->write(json_encode(['error' => 'Faltan datos del usuario']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
    }

    $userCreated = $db->createUser($UUID, $firstName, $lastName);

    if (!$userCreated) {
        $response->getBody()->write(json_encode(['error' => 'Error al crear el usuario']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(500);
    }

    $response->getBody()->write(json_encode(['message' => 'Usuario creado con éxito', 'Usuario' => $user]));
    return $response;
});

$app->put('/user/{UUID}', function (Request $request, Response $response, $args) use ($db) {

    $UUID = $args['UUID'];
    $newUserData = $request->getParsedBody();

    if (empty($newUserData)) {
        $response->getBody()->write(json_encode(['error' => 'Datos del usuario inválidos']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
    }

    $firstName = $newUserData['firstName'] ?? null;
    $lastName = $newUserData['lastName'] ?? null;

    if ($firstName && !$db->updateUserName($UUID, $firstName)) {
        $response->getBody()->write(json_encode(['error' => 'Error al actualizar el nombre del usuario']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(500);
    }

    if ($lastName && !$db->updateUserLastName($UUID, $lastName)) {
        $response->getBody()->write(json_encode(['error' => 'Error al actualizar el apellido del usuario']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(500);
    }

    $response->getBody()->write(json_encode(['message' => 'Usuario actualizado con éxito']));
    return $response;
});

$app->delete('/user/{UUID}', function (Request $request, Response $response, $args) use ($db) {

    $UUID = $args['UUID'];

    if (!$UUID) {
        $response->getBody()->write(json_encode(['error' => 'UUID no proporcionado']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
    }

    if (!$db->deleteUser($UUID)) {
        $response->getBody()->write(json_encode(['error' => 'Error al eliminar el usuario']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(500);
    }

    $response->getBody()->write(json_encode(['message' => 'Usuario eliminado con éxito']));
    return $response;
});

$app->setBasePath('/programacion-II/api/public');

$app->run();