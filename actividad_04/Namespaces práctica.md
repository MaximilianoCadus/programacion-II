## Repaso rápido

# Namespaces

Los *namespaces* en PHP organizan el código en espacios de nombres para evitar conflictos. Se declaran con `namespace`, como en:

```php
namespace App\Models;

class User {
    public function getName() {
        return "Usuario";
    }
}
```

---

## Relación entre Namespaces y Rutas de Archivos

Los namespaces reflejan la estructura de directorios. Por ejemplo:

```
App\Models\User → src/App/Models/User.php
```

Se usa con **autoloading** para cargar clases automáticamente.

---

## Ejemplo

```php
use App\Models\User;

$user = new User();
echo $user->getName();
```

---

# Práctica

1. Crear una clase `Usuario` dentro del namespace `Modelos`. La clase debe tener un método `decirHola()` que devuelva el mensaje `"Hola desde Usuario"`. Usar esa clase desde otro archivo.

2. Crear una clase `Persona` en el namespace `Base` con un método `saludar()`. Luego, crear la clase `Empleado` en el namespace `Modelos` que herede de `Persona` y tenga su propio método `trabajar()`.

3. Suponer que hay una clase `Ayudante` en `Proveedor\Herramientas`. Importar esta clase usando un alias `AyudaProveedor` y llamar a su método estático `ayudar()`.

4. Crear una interfaz `Renderable` en el namespace `Contratos` con el método `renderizar()`. Luego, crear una clase `Vista` en el namespace `Vistas` que implemente esa interfaz.

5. Configurar autoloading usando `spl_autoload_register`. Cargar automáticamente las clases del namespace `Modelos` desde `src/Modelos/`.

6. Crear una clase `ControladorUsuario` en el namespace `Controladores`. Incluir un método `inicio()` que devuelva `"Página de usuarios"`.

7. Crear una clase `Matematica` en el namespace `Utilidades` con un método estático `sumar($a, $b)` que retorne la suma de los dos parámetros.

8. Crear una clase `ConfiguracionApp` en el namespace `Configuracion` con una constante `NOMBRE_APP`. Acceder al valor de esta constante desde otro archivo.

9. Crear un archivo `funciones.php` en el namespace `Ayudantes` que contenga una función `saludar()`. Usar esa función desde otro archivo.

10. Crear una clase `Usuario` en el namespace `Modelos` con un método `obtenerNombre()`. Luego, crear una clase `ControladorUsuario` en el namespace `Controladores` que utilice `Usuario` para mostrar el nombre del usuario.
