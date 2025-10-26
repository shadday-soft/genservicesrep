# Procedimiento Almacenado para Búsqueda de Solicitudes

## Estado Actual

El repositorio `SolicitudRepository` ha sido optimizado con una **query SQL directa con paginación a nivel de base de datos**. Esto ofrece:

✅ **Ventajas:**
- Paginación eficiente (solo trae los datos necesarios)
- Usa JOINs en lugar de subqueries (más rápido)
- No requiere procedimientos almacenados (evita problemas de permisos)
- Bindings seguros contra SQL injection
- Compatible con cualquier versión de MySQL/MariaDB

## Solución Implementada

### 1. Query Optimizada con Paginación

La consulta actual en `SolicitudRepository.php`:
- Calcula el `OFFSET` basado en la página actual
- Usa `LIMIT` y `OFFSET` para traer solo los registros necesarios
- Ejecuta dos queries: una para el total y otra para los datos
- Usa LEFT JOINs para incluir todas las relaciones
- Hidrata los resultados como modelos de Laravel con relaciones precargadas

### 2. Rendimiento

**Comparación de enfoques:**

| Enfoque | Transferencia de datos | Procesamiento | Recomendado |
|---------|----------------------|---------------|-------------|
| Opción 1: Traer todo y paginar en Laravel | ❌ Alto | ❌ PHP procesa todo | No |
| **Opción 2: Paginar en BD (implementada)** | ✅ Mínimo | ✅ MySQL procesa | **Sí** |
| Opción 3: Procedimiento almacenado | ✅ Mínimo | ✅ MySQL procesa | Sí (si está disponible) |

## Migrar a Procedimiento Almacenado (Opcional)

Si deseas usar el procedimiento almacenado en el futuro, sigue estos pasos:

### Paso 1: Corregir el Sistema de MariaDB

El error actual es: `Column count of mysql.proc is wrong`. Esto se soluciona:

```bash
# Detener el servicio MySQL/MariaDB
sudo systemctl stop mysql

# Actualizar las tablas del sistema
sudo mysql_upgrade --force

# Reiniciar el servicio
sudo systemctl start mysql
```

### Paso 2: Ejecutar la Migración

```bash
php artisan migrate
```

Esto creará el procedimiento almacenado `sp_search_solicitudes`.

### Paso 3: Actualizar el Repository

Reemplaza el método `getAll` en `SolicitudRepository.php`:

```php
public function getAll($perPage = 15, $search = null)
{
    $currentPage = request()->get('page', 1);
    
    // Llamar al procedimiento almacenado
    $pdo = DB::connection()->getPdo();
    $stmt = $pdo->prepare('CALL sp_search_solicitudes(?, ?, ?)');
    $stmt->execute([$search, $currentPage, $perPage]);
    
    // Obtener los datos (primer result set)
    $items = $stmt->fetchAll(\PDO::FETCH_OBJ);
    
    // Obtener el total (segundo result set)
    $stmt->nextRowset();
    $totalResult = $stmt->fetch(\PDO::FETCH_OBJ);
    $total = $totalResult->total ?? 0;
    
    // Hidratar los resultados como modelos...
    // (mismo código de hidratación que en la versión actual)
    
    return new LengthAwarePaginator(
        $solicitudes,
        $total,
        $perPage,
        $currentPage,
        ['path' => request()->url(), 'query' => request()->query()]
    );
}
```

## Índices Recomendados

Para optimizar aún más el rendimiento, agrega estos índices:

```php
// Nueva migración: create_solicitudes_search_indexes
Schema::table('solicituds', function (Blueprint $table) {
    $table->index('numero_orden');
    $table->index('estado');
    $table->index('prioridad');
    $table->index('created_at');
    $table->index(['client_id', 'estado']);
    $table->index(['sucursal_id', 'estado']);
});

Schema::table('clients', function (Blueprint $table) {
    $table->index('enterprise_name');
});

Schema::table('sucursals', function (Blueprint $table) {
    $table->index('name');
});

Schema::table('equipos', function (Blueprint $table) {
    $table->index('nombre_equipo');
    $table->index('tipo_equipo');
});

Schema::table('users', function (Blueprint $table) {
    // Ya debería tener índice en 'name', verificar
});
```

## Testing

Prueba el rendimiento con datos de producción:

```php
// En Tinker
php artisan tinker

// Probar sin búsqueda
$repo = app(\App\Repositories\SolicitudRepository::class);
$result = $repo->getAll(15, null);

// Probar con búsqueda
$result = $repo->getAll(15, 'test');

// Verificar tiempo de ejecución
DB::enableQueryLog();
$result = $repo->getAll(15, 'test');
dd(DB::getQueryLog());
```

## Notas

- La solución actual (query optimizada) es **preferible** a un procedimiento almacenado para este caso
- Los procedimientos almacenados son útiles para lógica compleja, pero aquí solo tenemos JOINs y filtros
- La migración del procedimiento almacenado está lista para cuando lo necesites
- Recuerda agregar los índices para maximizar el rendimiento
