# Guía de Configuración de Docentes

## Resumen de Cambios

Se han realizado mejoras en el sistema para asegurar que los campos `area` y `departamento` se carguen correctamente desde `config/docentes.php` al formulario de bienvenida del docente.

## Archivos Modificados

1. **app/Http/Controllers/SessionsController.php**
   - Mejorada la lógica de carga de datos desde config
   - Añadido mejor manejo de errores con `isset()` para evitar índices no definidos
   - Agregado logging para facilitar debugging
   - Los datos se sincronizan en `UsersResponseForm1` durante el login

2. **app/Http/Controllers/DashboardController.php**
   - Actualizado el método `returnWelcomeView()` para pasar correctamente las variables `$nombre`, `$area` y `$departamento` a la vista
   - Este es el controlador que realmente sirve la página welcome según las rutas

3. **config/docentes.php**
   - Añadida documentación clara sobre cómo alinear los arrays
   - Comentarios que indican la correspondencia entre índices

## Cómo Funciona

### Flujo de Datos

```
config/docentes.php → SessionsController::login() → UsersResponseForm1 (DB) → DashboardController::returnWelcomeView() → welcome.blade.php
```

1. **Al hacer login**: El sistema busca el email del usuario en `config/docentes.php`
2. **Guarda en BD**: Los datos (nombre, area, departamento) se guardan en `users_responses_form1`
3. **Al mostrar welcome**: La ruta `welcome` llama a `DashboardController::index()`, que a su vez llama a `returnWelcomeView()`, donde se cargan los datos desde la BD y config, y se pasan a la vista

### Verificación de Arrays en config/docentes.php

**IMPORTANTE**: Todos los arrays deben tener la misma cantidad de elementos y estar alineados:

```php
'emails' => [
    'user1@uabcs.mx',    // Índice 0
    'user2@uabcs.mx',    // Índice 1
    'user3@uabcs.mx',    // Índice 2
],
'nombres' => [
    'Nombre Usuario 1',  // Índice 0 - corresponde a user1@uabcs.mx
    'Nombre Usuario 2',  // Índice 1 - corresponde a user2@uabcs.mx
    'Nombre Usuario 3',  // Índice 2 - corresponde a user3@uabcs.mx
],
'departamentos' => [
    'Departamento 1',    // Índice 0 - corresponde a user1@uabcs.mx
    'Departamento 2',    // Índice 1 - corresponde a user2@uabcs.mx
    'Departamento 3',    // Índice 2 - corresponde a user3@uabcs.mx
],
'areas' => [
    'Área 1',            // Índice 0 - corresponde a user1@uabcs.mx
    'Área 2',            // Índice 1 - corresponde a user2@uabcs.mx
    'Área 3',            // Índice 2 - corresponde a user3@uabcs.mx
],
```

## Cómo Probar

### 1. Verificar Configuración

Asegúrese de que `config/docentes.php` tenga todos los arrays alineados correctamente.

### 2. Limpiar Caché de Configuración

```bash
php artisan config:clear
php artisan cache:clear
```

### 3. Probar el Login

1. Inicie sesión con un usuario docente cuyo email esté en `config/docentes.php`
2. Verifique que los campos en la página welcome muestren:
   - Nombre correcto
   - Área de conocimiento correcta
   - Departamento académico correcto

### 4. Revisar Logs (para debugging)

Los cambios incluyen logging detallado. Puede revisar los logs en:

```bash
storage/logs/laravel.log
```

Busque entradas como:
- `Docente login data saved`
- `Dual-role docente login data saved`
- `Welcome page data prepared`

Ejemplo de log:
```
[2026-01-27 ...] local.INFO: Docente login data saved {"user_id":123,"email":"user@uabcs.mx","nombre":"Nombre Usuario","area":"Área de Conocimiento","departamento":"Departamento Académico","config_index":0}
```

## Solución de Problemas

### Problema: Los campos muestran "No definida" o "No definido"

**Causas posibles:**
1. El email del usuario no está en `config/docentes.php`
2. Los arrays no están alineados correctamente
3. El índice del usuario no tiene valores correspondientes en todos los arrays

**Solución:**
1. Verifique que el email esté en el array `emails`
2. Cuente los elementos de cada array - deben ser iguales
3. Verifique que cada índice tenga un valor en todos los arrays
4. Ejecute `php artisan config:clear` después de modificar el config

### Problema: Los datos no se actualizan

**Solución:**
1. Limpie el caché: `php artisan config:clear`
2. Cierre sesión y vuelva a iniciar sesión
3. Revise los logs para verificar qué datos se están guardando

### Problema: Error de índice no definido

Esto ya está solucionado en el código con verificaciones `isset()`, pero si ocurre:
1. Verifique que todos los arrays tengan la misma longitud
2. No deje elementos vacíos o comentados en medio de los arrays

## Comando Útil para Verificar Arrays

Puede crear un comando artisan temporal para verificar la configuración:

```bash
php artisan tinker
```

Luego ejecute:
```php
$emails = config('docentes.emails');
$nombres = config('docentes.nombres');
$deptos = config('docentes.departamentos');
$areas = config('docentes.areas');

echo "Emails: " . count($emails) . "\n";
echo "Nombres: " . count($nombres) . "\n";
echo "Departamentos: " . count($deptos) . "\n";
echo "Areas: " . count($areas) . "\n";
```

Todos deben mostrar el mismo número.

## Notas Adicionales

- Los campos son **readonly** en el formulario - los docentes no pueden editarlos
- Los datos se actualizan cada vez que el usuario inicia sesión
- Si necesita cambiar los datos de un docente, edite `config/docentes.php` y el usuario debe cerrar sesión y volver a iniciar sesión
- Los cambios en `config/docentes.php` requieren `php artisan config:clear` en producción
