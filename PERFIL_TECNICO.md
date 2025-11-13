# Funcionalidad: Perfil de Técnico

## Descripción
Los técnicos ahora pueden actualizar su información personal desde la vista de perfil (`/settings/profile`). El sistema sincroniza automáticamente los cambios entre el modelo `Tecnico` y el modelo `User` relacionado.

## Campos Editables para Técnicos

1. **Nombre Completo** - Campo requerido
2. **Identificación** - Campo requerido, único, mínimo 8 caracteres
3. **Correo Electrónico** - Campo requerido, único, formato email
4. **Persona de Contacto** - Campo opcional
5. **Teléfono de Contacto** - Campo opcional
6. **Dirección de Contacto** - Campo opcional

## Sincronización con Usuario

Cuando un técnico actualiza su perfil:
- `User.name` se actualiza con `Tecnico.nombre_completo`
- `User.email` se actualiza con `Tecnico.correo`
- `User.email_verified_at` se resetea a `null` si el correo cambió

## Validaciones

- **Identificación**: Debe ser única entre todos los técnicos
- **Correo**: Debe ser único tanto en la tabla `tecnicos` como en `users`
- **Campos requeridos**: nombre_completo, identificacion, correo
- **Longitud mínima**: identificacion (8 caracteres)

## Archivos Modificados

### Backend
- `app/Http/Controllers/Settings/ProfileController.php` - Lógica principal
- `app/Http/Requests/Settings/TecnicoProfileUpdateRequest.php` - Validación

### Frontend
- `resources/js/pages/settings/Profile.vue` - Interfaz de usuario

### Tests
- `tests/Feature/Settings/TecnicoProfileUpdateTest.php` - Suite de tests

## Uso

### Para Técnicos
1. Inicia sesión como técnico
2. Ve a "Configuración" → "Perfil"
3. Actualiza los campos deseados
4. Haz clic en "Guardar"

### Para Usuarios Regulares
Los usuarios con rol `Administrador` o `Cliente` continuarán viendo solo los campos `name` y `email`.

## Ejemplo de Código

```php
// El controlador detecta automáticamente el tipo de usuario
if ($user->role === 'Tecnico') {
    return $this->updateTecnicoProfile($request);
}
```

```vue
<!-- La vista muestra campos diferentes según el rol -->
<template v-if="isTecnico && props.tecnico">
    <!-- Campos de técnico -->
</template>
<template v-else>
    <!-- Campos de usuario regular -->
</template>
```

## Tests

Ejecutar tests:
```bash
php artisan test --filter=TecnicoProfileUpdateTest
```

## Notas Técnicas

- Se usa transacción de base de datos para garantizar consistencia
- Los cambios se realizan en un solo request
- El email_verified_at se maneja automáticamente
- Compatible con Inertia.js v2
