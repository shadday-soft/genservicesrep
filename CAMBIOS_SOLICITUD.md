# Cambios en el Módulo de Solicitudes

## 📋 Resumen de Cambios

Se han añadido dos nuevas columnas a la tabla de solicitudes para mejorar la gestión de información:

1. **Tipo de Mantenimiento**: Clasificación automática entre "Mantenimiento Preventivo" y "Mantenimiento Correctivo"
2. **Firma Cliente**: Nombre de la persona que firma por parte del cliente al generar el informe

---

## 🗄️ Cambios en Base de Datos

### Nueva Migración
**Archivo**: `database/migrations/2025_11_19_130204_add_tipo_mantenimiento_and_firma_cliente_to_solicituds_table.php`

```php
Schema::table('solicituds', function (Blueprint $table) {
    $table->enum('tipo_mantenimiento', ['Mantenimiento Preventivo', 'Mantenimiento Correctivo'])
          ->nullable()
          ->after('actividad');
    $table->string('firma_cliente')
          ->nullable()
          ->after('tipo_mantenimiento');
});
```

### Ejecutar Migración
```bash
php artisan migrate
```

---

## 🔧 Cambios en el Backend

### 1. Modelo Solicitud (`app/Models/Solicitud.php`)

#### Accessor para Tipo de Mantenimiento
```php
public function getTipoMantenimientoDisplayAttribute(): string
{
    if ($this->tipo_mantenimiento) {
        return $this->tipo_mantenimiento;
    }

    return $this->actividad === 'Mantenimiento Preventivo'
        ? 'Mantenimiento Preventivo'
        : 'Mantenimiento Correctivo';
}
```

#### Asignación Automática en Creación
El tipo de mantenimiento se asigna automáticamente al crear una solicitud basándose en el campo `actividad`:
- Si `actividad` es "Mantenimiento Preventivo" → tipo = "Mantenimiento Preventivo"
- Si `actividad` es cualquier otra cosa → tipo = "Mantenimiento Correctivo"

### 2. Repositorio de Solicitudes (`app/Repositories/SolicitudRepository.php`)

Se añadió un mapeo en el método `getAllSolicitudes()` para asegurar que todas las solicitudes tengan el campo `tipo_mantenimiento` poblado:

```php
$result->getCollection()->transform(function ($solicitud) {
    if (!$solicitud->tipo_mantenimiento) {
        $solicitud->tipo_mantenimiento = $solicitud->actividad === 'Mantenimiento Preventivo'
            ? 'Mantenimiento Preventivo'
            : 'Mantenimiento Correctivo';
    }
    return $solicitud;
});
```

### 3. Repositorio de Equipos (`app/Repositories/EquipoRepository.php`)

Cuando se crean solicitudes automáticas al registrar un equipo, ahora se asigna el tipo:

```php
Solicitud::create([
    'tipo_mantenimiento' => 'Mantenimiento Preventivo',
    'client_id' => $equipo->client_id,
    // ... resto de campos
]);
```

### 4. Repositorio de Informes (`app/Repositories/InformeRepository.php`)

Al crear un informe, se guarda el nombre de quien firma en la solicitud:

```php
if (isset($data['nombre_cliente'])) {
    $solicitud->firma_cliente = $data['nombre_cliente'];
}
```

### 5. Request de Informe (`app/Http/Requests/StoreInformeRequest.php`)

El campo `nombre_cliente` ahora es obligatorio:

```php
'nombre_cliente' => ['required', 'string', 'max:100'],
```

---

## 🎨 Cambios en el Frontend

### 1. Columnas de la Tabla (`resources/js/pages/Solicituds/Columns.ts`)

#### Nueva Columna: Tipo de Mantenimiento
```typescript
{
    header: 'Tipo de Mantenimiento',
    field: 'tipo_mantenimiento_display',
    sortable: true,
    type: 'tag',
    filter: true,
    filterPlaceholder: 'Buscar por tipo',
    tags: [
        {
            label: 'Preventivo',
            value: 'Mantenimiento Preventivo',
            severity: 'info',
        },
        {
            label: 'Correctivo',
            value: 'Mantenimiento Correctivo',
            severity: 'warn',
        },
    ]
}
```

#### Nueva Columna: Firma Cliente
```typescript
{
    header: 'Firma Cliente',
    field: 'firma_cliente',
    sortable: true,
    type: 'text',
    filter: true,
    filterPlaceholder: 'Buscar por firma',
}
```

### 2. Vista Index (`resources/js/pages/Solicituds/Index.vue`)

#### Ocultación Condicional del Botón "Agregar Solicitud"

El botón ahora se oculta cuando el parámetro `tipo` es `null` o `undefined`:

```vue
<Button 
    label="Agregar Solicitud" 
    v-if="(isAutorized() || ($page.props.auth.user.role === 'Cliente' && props.filters?.tipo === 'Mantenimiento Correctivo')) && props.filters?.tipo" 
    icon="pi pi-plus" 
    size="small" 
    @click="add" 
/>
```

**Comportamiento**:
- ✅ **Administrador**: Ve el botón siempre que haya un tipo definido
- ✅ **Cliente**: Ve el botón solo para "Mantenimiento Correctivo" y cuando hay tipo definido
- ❌ **Cuando `tipo` es null**: El botón NO se muestra para ningún rol

---

## 📊 Flujo de Datos

### Creación de Solicitud
```
1. Usuario crea solicitud con actividad
   ↓
2. Modelo asigna tipo_mantenimiento automáticamente
   ↓
3. Se guarda en BD con tipo_mantenimiento poblado
```

### Listado de Solicitudes
```
1. Repositorio obtiene solicitudes de BD
   ↓
2. Transform mapea tipo_mantenimiento si falta
   ↓
3. Frontend muestra columna con badge de color
```

### Generación de Informe
```
1. Usuario genera informe y firma
   ↓
2. nombre_cliente se valida (requerido)
   ↓
3. Al guardar informe:
   - Se marca solicitud como informe_generado = true
   - Se guarda firma_cliente = nombre_cliente
```

---

## 🎯 Casos de Uso

### Caso 1: Solicitud Manual (Correctiva)
```php
// El usuario crea manualmente una solicitud
$solicitud = Solicitud::create([
    'actividad' => 'Reparación Urgente',
    // ... otros campos
]);

// ✅ Automáticamente: tipo_mantenimiento = "Mantenimiento Correctivo"
```

### Caso 2: Solicitud Automática (Preventiva)
```php
// Sistema crea solicitud al registrar equipo
$solicitud = Solicitud::create([
    'tipo_mantenimiento' => 'Mantenimiento Preventivo',
    'actividad' => 'Mantenimiento Preventivo',
    // ... otros campos
]);

// ✅ tipo_mantenimiento = "Mantenimiento Preventivo" (explícito)
```

### Caso 3: Informe con Firma
```php
// Usuario genera informe
$informe = Informe::create([
    'solicitud_id' => $solicitud->id,
    'nombre_cliente' => 'Juan Pérez', // Obligatorio
    // ... otros campos
]);

// ✅ solicitud->firma_cliente = "Juan Pérez"
// ✅ solicitud->informe_generado = true
```

---

## ✅ Testing

### Verificar Migración
```bash
php artisan migrate:status
```

### Verificar en Tinker
```php
php artisan tinker

// Crear solicitud de prueba
$solicitud = \App\Models\Solicitud::create([
    'client_id' => 1,
    'sucursal_id' => 1,
    'equipo_id' => 1,
    'actividad' => 'Revisión General',
    // ... campos requeridos
]);

// Verificar tipo asignado
$solicitud->tipo_mantenimiento; // "Mantenimiento Correctivo"
$solicitud->tipo_mantenimiento_display; // "Mantenimiento Correctivo"
```

### Verificar en Frontend
1. Ir a `/solicituds`
2. Verificar que la columna "Tipo de Mantenimiento" se muestre
3. Verificar que la columna "Firma Cliente" se muestre
4. Sin parámetro `tipo`: botón "Agregar" debe estar oculto
5. Con parámetro `tipo`: botón "Agregar" debe estar visible

---

## 🚀 Despliegue

1. **Ejecutar migración**:
   ```bash
   php artisan migrate
   ```

2. **Limpiar caché** (opcional):
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   ```

3. **Compilar assets** (si es necesario):
   ```bash
   npm run build
   # o
   composer run dev
   ```

4. **Verificar columnas en la tabla**:
   ```sql
   DESCRIBE solicituds;
   -- Debe mostrar:
   -- tipo_mantenimiento enum('Mantenimiento Preventivo','Mantenimiento Correctivo')
   -- firma_cliente varchar(255)
   ```

---

## 📝 Notas Importantes

1. **Retrocompatibilidad**: Las solicitudes existentes sin `tipo_mantenimiento` se mapean automáticamente usando el campo `actividad`

2. **Valores por defecto**: El campo `tipo_mantenimiento` es nullable, pero siempre se asigna en la creación

3. **Firma Cliente**: Solo se llena al generar el informe, permanece null hasta ese momento

4. **Botón Agregar**: Se oculta cuando no hay contexto de tipo (lista general de solicitudes)

---

## 🐛 Solución de Problemas

### Problema: Columna tipo_mantenimiento no existe
```bash
# Verificar que la migración se ejecutó
php artisan migrate:status

# Si no está ejecutada
php artisan migrate
```

### Problema: El botón "Agregar" no se oculta
- Verificar que el parámetro `tipo` se está pasando correctamente en la URL
- Revisar en DevTools: `props.filters?.tipo` debe ser `null` o `undefined`

### Problema: Tipo de mantenimiento no se muestra
- Verificar que el accessor `tipo_mantenimiento_display` está en el modelo
- Verificar que el campo está en `$appends` del modelo

---

## 📚 Referencias

- **Modelo**: `app/Models/Solicitud.php`
- **Migración**: `database/migrations/2025_11_19_130204_add_tipo_mantenimiento_and_firma_cliente_to_solicituds_table.php`
- **Columnas**: `resources/js/pages/Solicituds/Columns.ts`
- **Vista**: `resources/js/pages/Solicituds/Index.vue`
