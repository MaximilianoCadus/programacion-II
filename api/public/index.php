<?php

use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Tuupola\Middleware\HttpBasicAuthentication;
use Tuupola\Middleware\JwtAuthentication;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require __DIR__ . '/../vendor/autoload.php';
require_once 'db_connection.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$host = $_ENV['HOST'];
$dbname = $_ENV['DBNAME'];
$username = $_ENV['USERNAME'];
$password = $_ENV['PASSWORD'];
$basePath = $_ENV['BASEPATH'];
$jwtKey = $_ENV['KEY'];
$jwtSecret = $_ENV['SECRET'];

$db = new Database($host, $dbname, $username, $password);

$app->post('/login', function (Request $request, Response $response) use ($db) {
    $authHeader = $request->getHeaderLine('Authorization');

    if (!$authHeader || !str_starts_with($authHeader, 'Basic ')) {
        $response->getBody()->write(json_encode(['error' => 'Se requiere autenticación Basic Auth']));
        return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }

    $credentials = base64_decode(substr($authHeader, 6));
    $parts = explode(':', $credentials, 2);

    if (count($parts) !== 2) {
        $response->getBody()->write(json_encode(['error' => 'Formato de credenciales inválido']));
        return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }

    $email = $parts[0];
    $password = $parts[1];

    $user = $db->getUserByEmail($email);

    if (!$user) {
        $response->getBody()->write(json_encode(['error' => 'Usuario no encontrado']));
        return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }

    if ($user['pswd'] !== $password) {
        $response->getBody()->write(json_encode(['error' => 'Contraseña incorrecta']));
        return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }

    $rol_id = $user['rol_id'];
    $rol = ($rol_id == 1) ? 'admin' : 'usuario';

    $payload = [
        "iss" => "example.com",
        "aud" => "example.com",
        "iat" => time(),
        "nbf" => time(),
        "exp" => time() + 3600,
        "user" => [
            "email" => $email,
            "role" => $rol
        ]
    ];

    $token = JWT::encode($payload, $jwtSecret, 'HS256');

    $response->getBody()->write(json_encode([
        "token" => $token,
        "user" => [
            "email" => $email,
            "role" => $rol
        ]
    ]));

    return $response->withHeader('Content-Type', 'application/json');
});

$app->add(new JwtAuthentication([
    "secret" => $jwtSecret,
    "attribute" => "token",
    "secure" => false,
    "path" => [
        $basePath . "/recursos",
        "/recursos"
    ],
    "ignore" => [
        $basePath . "/login",
        "/login"
    ],
    "algorithm" => ["HS256"],
    "error" => function ($response, $arguments) {
        $data = [
            "error" => "Token inválido o ausente",
            "message" => $arguments['message'] ?? 'Unauthorized'
        ];
        $payload = json_encode($data, JSON_UNESCAPED_UNICODE);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
]));

class AuthMiddleware
{
    private string $jwtSecret;

    public function __construct(string $jwtSecret)
    {
        $this->jwtSecret = $jwtSecret;
    }

    public function __invoke(Request $request, Handler $handler): Response
    {
        $authHeader = $request->getHeaderLine('Authorization');
        if (!$authHeader) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['error' => 'Token requerido']));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        $token = str_replace('Bearer ', '', $authHeader);

        try {
            $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
            $request = $request->withAttribute('user', json_decode(json_encode($decoded), true));
        } catch (\Exception $e) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['error' => 'Token inválido']));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        return $handler->handle($request);
    }
}

class RoleMiddleware
{
    private array $allowedRoles;

    public function __construct(array $allowedRoles)
    {
        $this->allowedRoles = $allowedRoles;
    }

    public function __invoke(Request $request, Handler $handler): Response
    {
        $user = $request->getAttribute('user');
        if (!$user || !in_array($user['user']['role'], $this->allowedRoles)) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['error' => 'Acceso denegado']));
            return $response->withStatus(403)->withHeader('Content-Type', 'application/json');
        }
        return $handler->handle($request);
    }
}

$app->post('/register', function (Request $request, Response $response, $args) use ($db) {

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
    $email = $user['email'] ?? null;
    $pswd = $user['pswd'] ?? null;

    if (!$UUID || !$firstName || !$lastName || !$email || !$pswd) {
        $response->getBody()->write(json_encode(['error' => 'Faltan datos del usuario']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
    }

    $userCreated = $db->createUser($UUID, $firstName, $lastName, $email, $pswd);

    if (!$userCreated) {
        $response->getBody()->write(json_encode(['error' => 'Error al crear el usuario']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(500);
    }

    $response->getBody()->write(json_encode(['message' => 'Usuario creado con éxito', 'user' => ['uuid' => $UUID, 'firstName' => $firstName, 'lastName' => $lastName, 'email' => $email]]));
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
});

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
        $response->getBody()->write(json_encode(['message' => 'Usuario encontrado con éxito', 'user' => ['uuid' => $UUID, 'firstName' => $user['firstName'], 'lastName' => $user['lastName'], 'email' => $user['email']]]));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    $response->getBody()->write(json_encode(['error' => 'Usuario no encontrado']));
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(400);
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
    $email = $newUserData['email'] ?? null;

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

    if ($email && !$db->updateUserEmail($UUID, $email)) {
        $response->getBody()->write(json_encode(['error' => 'Error al actualizar el email del usuario']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(500);
    }

    $userData = array_filter([
        'uuid' => $UUID,
        'firstName' => $firstName,
        'lastName' => $lastName,
        'email' => $email
    ], function ($value) {
        return $value !== null;
    });

    $response->getBody()->write(json_encode(['message' => 'Usuario actualizado con éxito', 'user' => $userData]));
    return $response
        ->withHeader('Content-Type', 'application/json');
})->add(new RoleMiddleware(['usuario', 'admin']))
->add(new AuthMiddleware($jwtSecret));

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
    return $response
        ->withHeader('Content-Type', 'application/json');
})->add(new RoleMiddleware(['admin']))
->add(new AuthMiddleware($jwtSecret));

$app->get('/recursos', function (Request $request, Response $response) use ($db) {
    $recursos = $db->getAllResources();

    if ($recursos) {
        $response->getBody()->write(json_encode($recursos));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    $response->getBody()->write(json_encode(['error' => 'Error al obtener recursos']));
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(500);
});

$app->post('/recursos', function (Request $request, Response $response) use ($db) {
    $data = $request->getParsedBody();

    $required = ['nombre', 'capacidad'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            $response->getBody()->write(json_encode(['error' => "Campo $field requerido"]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }
    }

    $createdId = $db->createResource(
        $data['nombre'],
        $data['descripcion'] ?? '',
        $data['capacidad']
    );

    if ($createdId) {
        $response->getBody()->write(json_encode([
            'message' => 'Recurso creado',
            'id' => $createdId
        ]));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }

    $response->getBody()->write(json_encode(['error' => 'Error al crear recurso']));
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(500);
})
->add(new RoleMiddleware(['admin']))
->add(new AuthMiddleware($jwtSecret));

$app->get('/recursos/disponibles', function (Request $request, Response $response) use ($db) {
    $params = $request->getQueryParams();

    if (empty($params['fecha_inicio']) || empty($params['fecha_fin'])) {
        $response->getBody()->write(json_encode(['error' => 'Parámetros fecha_inicio y fecha_fin requeridos']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
    }

    $disponibles = $db->getAvailableResources(
        $params['fecha_inicio'],
        $params['fecha_fin']
    );

    if ($disponibles !== false) {
        $response->getBody()->write(json_encode($disponibles));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    $response->getBody()->write(json_encode(['error' => 'Error al verificar disponibilidad']));
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(500);
});

$app->get('/recursos/{id}', function (Request $request, Response $response, $args) use ($db) {
    $id = $args['id'];

    if (!$id) {
        $response->getBody()->write(json_encode(['error' => 'ID no proporcionado']));
        return $response->withStatus(400);
    }

    $recurso = $db->getResource($id);

    if ($recurso) {
        $response->getBody()->write(json_encode($recurso));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    $response->getBody()->write(json_encode(['error' => 'Recurso no encontrado']));
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(404);
});

$app->get('/reservas/usuario/{UUID}', function (Request $request, Response $response, $args) use ($db) {
    $UUID = $args['UUID'];

    if (!$UUID) {
        $response->getBody()->write(json_encode(['error' => 'UUID no proporcionado']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
    }

    $reservas = $db->getReservationsByUser($UUID);

    if ($reservas !== false) {
        $response->getBody()->write(json_encode($reservas));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    $response->getBody()->write(json_encode(['error' => 'Error al obtener reservas']));
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(500);
});

$app->post('/reservas/{id}/confirmar', function (Request $request, Response $response, $args) use ($db) {
    $id = $args['id'];

    if (!$id) {
        $response->getBody()->write(json_encode(['error' => 'ID de reserva no proporcionado']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
    }

    if ($db->confirmReservation($id)) {
        $response->getBody()->write(json_encode(['message' => 'Reserva confirmada y notificada']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    $response->getBody()->write(json_encode(['error' => 'Error al confirmar reserva']));
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(500);
})->add(new RoleMiddleware(['admin']))
->add(new AuthMiddleware($jwtSecret));

$app->post('/reservas', function (Request $request, Response $response) use ($db) {
    $data = $request->getParsedBody();

    $required = ['recurso_id', 'usuario_UUID', 'fecha_inicio', 'fecha_fin'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            $response->getBody()->write(json_encode(['error' => "Campo $field requerido"]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }
    }

    if (!$db->checkResourceAvailability(
        $data['recurso_id'],
        $data['fecha_inicio'],
        $data['fecha_fin']
    )) {
        $response->getBody()->write(json_encode(['error' => 'Recurso no disponible en esas fechas']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(409);
    }

    $reservaId = $db->createReservation(
        $data['recurso_id'],
        $data['usuario_UUID'],
        $data['fecha_inicio'],
        $data['fecha_fin']
    );

    if ($reservaId) {
        $response->getBody()->write(json_encode([
            'message' => 'Reserva creada',
            'id' => $reservaId
        ]));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }

    $response->getBody()->write(json_encode(['error' => 'Error al crear reserva']));
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(500);
})->add(new RoleMiddleware(['admin']))
->add(new AuthMiddleware($jwtSecret));

$app->put('/recursos/{id}', function (Request $request, Response $response, $args) use ($db) {
    $id = $args['id'];
    $data = $request->getParsedBody();

    if (!$id) {
        $response->getBody()->write(json_encode(['error' => 'ID no proporcionado']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
    }

    if (empty($data)) {
        $response->getBody()->write(json_encode(['error' => 'Datos de recurso inválidos']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
    }

    $updateFields = [];
    if (isset($data['nombre'])) {
        if (!$db->updateResourceName($id, $data['nombre'])) {
            $response->getBody()->write(json_encode(['error' => 'Error al actualizar nombre del recurso']));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }

    if (isset($data['descripcion'])) {
        if (!$db->updateResourceDescription($id, $data['descripcion'])) {
            $response->getBody()->write(json_encode(['error' => 'Error al actualizar descripción del recurso']));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }

    if (isset($data['capacidad'])) {
        if (!$db->updateResourceCapacity($id, $data['capacidad'])) {
            $response->getBody()->write(json_encode(['error' => 'Error al actualizar capacidad del recurso']));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }

    $response->getBody()->write(json_encode(['message' => 'Recurso actualizado con éxito']));
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
})->add(new RoleMiddleware(['usuario', 'admin']))
->add(new AuthMiddleware($jwtSecret));

$app->delete('/recursos/{id}', function (Request $request, Response $response, $args) use ($db) {
    $id = $args['id'];

    if (!$id) {
        $response->getBody()->write(json_encode(['error' => 'ID no proporcionado']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
    }

    if ($db->hasActiveReservations($id)) {
        $response->getBody()->write(json_encode(['error' => 'No se puede eliminar, tiene reservas activas']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(409);
    }

    if (!$db->deleteResource($id)) {
        $response->getBody()->write(json_encode(['error' => 'Error al eliminar recurso']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(500);
    }

    $response->getBody()->write(json_encode(['message' => 'Recurso eliminado con éxito']));
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
})->add(new RoleMiddleware(['admin']))
->add(new AuthMiddleware($jwtSecret));

$app->put('/reservas/{id}', function (Request $request, Response $response, $args) use ($db) {
    $id = $args['id'];
    $data = $request->getParsedBody();

    if (!$id) {
        $response->getBody()->write(json_encode(['error' => 'ID de reserva no proporcionado']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
    }

    if (empty($data)) {
        $response->getBody()->write(json_encode(['error' => 'Datos de reserva inválidos']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
    }

    if (isset($data['fecha_inicio']) || isset($data['fecha_fin']) || isset($data['recurso_id'])) {
        $reserva = $db->getReservation($id);
        if (!$reserva) {
            $response->getBody()->write(json_encode(['error' => 'Reserva no encontrada']));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $recurso_id = $data['recurso_id'] ?? $reserva['recurso_id'];
        $fecha_inicio = $data['fecha_inicio'] ?? $reserva['fecha_inicio'];
        $fecha_fin = $data['fecha_fin'] ?? $reserva['fecha_fin'];

        if (!$db->checkResourceAvailabilityExcluding(
            $recurso_id,
            $fecha_inicio,
            $fecha_fin,
            $id
        )) {
            $response->getBody()->write(json_encode(['error' => 'Recurso no disponible en esas fechas']));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(409);
        }
    }

    $updateFields = [];
    if (isset($data['recurso_id'])) {
        if (!$db->updateReservationResource($id, $data['recurso_id'])) {
            $response->getBody()->write(json_encode(['error' => 'Error al actualizar recurso de la reserva']));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }

    if (isset($data['fecha_inicio'])) {
        if (!$db->updateReservationStart($id, $data['fecha_inicio'])) {
            $response->getBody()->write(json_encode(['error' => 'Error al actualizar fecha de inicio']));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }

    if (isset($data['fecha_fin'])) {
        if (!$db->updateReservationEnd($id, $data['fecha_fin'])) {
            $response->getBody()->write(json_encode(['error' => 'Error al actualizar fecha de fin']));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }

    $response->getBody()->write(json_encode(['message' => 'Reserva actualizada con éxito']));
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
})->add(new RoleMiddleware(['usuario', 'admin']))
->add(new AuthMiddleware($jwtSecret));

$app->delete('/reservas/{id}', function (Request $request, Response $response, $args) use ($db) {
    $id = $args['id'];

    if (!$id) {
        $response->getBody()->write(json_encode(['error' => 'ID de reserva no proporcionado']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
    }

    if (!$db->deleteReservation($id)) {
        $response->getBody()->write(json_encode(['error' => 'Error al eliminar reserva']));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(500);
    }

    $response->getBody()->write(json_encode(['message' => 'Reserva eliminada con éxito']));
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
})->add(new RoleMiddleware(['admin']))
->add(new AuthMiddleware($jwtSecret));

$app->setBasePath($basePath);

$app->run();
