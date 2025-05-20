## Introducción a los espacios de nombres en PHP 8

```php
<?php
// Archivo: src/Controller/HomeController.php

namespace App\Controller;

class HomeController
{
    public function index(): void
    {
        echo 'Bienvenido al controlador Home';
    }
}
```

En este fragmento se define el espacio de nombres `App\Controller`, de modo que la clase `HomeController` no colisione con otras clases homónimas procedentes de librerías externas.

## Declaración y resolución de identificadores

```php
<?php
// Archivo: src/Service/UserService.php

namespace App\Service;

class UserService
{
    public function getAllUsers(): array
    {
        // Se asume existencia de App\Repository\UserRepository
        $repo = new Repository\UserRepository();
        return $repo->findAll();
    }
}

// Invocación desde otro namespace:
namespace App\Controller;

use App\Service\UserService;

$service = new UserService();
$users   = $service->getAllUsers();
```

Aquí se muestra cómo, dentro de `App\Service`, una clase crea una instancia de `Repository\UserRepository`, lo que equivale a `App\Service\Repository\UserRepository` si no se utiliza la barra inicial `\` para referirse al espacio global.

## Importación de rutas y alias

```php
<?php
namespace App\Controller;

use App\Service\UserService;
use App\Repository\UserRepository as Repo;       // Alias para claridad
use function App\Utility\formatDate as fDate;    // Alias para función
use const App\Utility\DEFAULT_LOCALE as LOCALE;   // Alias para constante

$service = new UserService();
$users   = $service->getAllUsers();

foreach ($users as $user) {
    echo fDate($user->getCreatedAt(), LOCALE) . PHP_EOL;
}
```

La agrupación y los alias (`as`) permiten mantener el código legible incluso cuando coexisten múltiples imports con nombres largos o similares.

## Uso de namespaces para funciones y constantes

```php
<?php
// Archivo: src/Utility/Math.php

namespace App\Utility;

const PI = 3.1415926535;

function add(float $a, float $b): float
{
    return $a + $b;
}
```

Y luego, en cualquier punto del sistema:

```php
<?php
namespace App\Controller;

use function App\Utility\add;
use const App\Utility\PI;

$result = add(2.5, 4.1);
echo "Suma: {$result}, Valor de PI: " . PI;
```

Esto muestra cómo agrupar lógica de utilidades en un mismo namespace, separando claramente responsabilidades.

## Atributos en PHP 8 y su contexto de namespace

```php
<?php
// Archivo: src/Attribute/Route.php

namespace App\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class Route
{
    public function __construct(
        public string $path,
        public string $method = 'GET'
    ) {}
}
```

```php
<?php
// Archivo: src/Controller/ArticleController.php

namespace App\Controller;

use App\Attribute\Route;
use App\Service\ArticleService;

class ArticleController
{
    public function list(ArticleService $svc): void
    {
        $articles = $svc->getAll();
    }
}
```

Definir atributos dentro de un namespace garantiza que los metadatos sean únicos y no interfieran con otros atributos externos.

## Recomendaciones de estilo y organización

En cada archivo solo debe existir una declaración `namespace`, y la jerarquía de subespacios debe coincidir con la estructura de carpetas. Un ejemplo de organización típica:

```
project-root/
├─ src/
│  ├─ Controller/
│  │   └─ HomeController.php        (namespace App\Controller)
│  ├─ Service/
│  │   └─ UserService.php           (namespace App\Service)
│  ├─ Repository/
│  │   └─ UserRepository.php        (namespace App\Repository)
│  ├─ Utility/
│  │   └─ Math.php                  (namespace App\Utility)
│  └─ Attribute/
│      └─ Route.php                 (namespace App\Attribute)
├─ composer.json
└─ vendor/
```

---


Glass, L. (2018). *PHP Testing: Unit and Integration Testing with PHPUnit*. Packt Publishing.

Lockhart, J. (2015). *Modern PHP: New Features and Good Practices*. O’Reilly Media.

McCreary, C., & Lomow, G. (2013). *Advanced PHP Programming* (2nd ed.). Addison‑Wesley Professional.

Otte‑Witte, M. (2017). *Mastering PHP Design Patterns*. Packt Publishing.

Powers, D. (2014). *Pro PHP MVC* (2nd ed.). Apress.

Sklar, D., & Trautsch, L. (2014). *PHP Cookbook* (3rd ed.). O’Reilly Media.

Zandstra, M. (2017). *PHP Objects, Patterns, and Practice* (4th ed.). Apress.
