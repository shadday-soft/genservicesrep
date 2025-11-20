# Configuración del Sistema de Correos

## Descripción
El sistema envía automáticamente correos de bienvenida con credenciales cuando se crean nuevos usuarios (Clientes o Técnicos).

## Funcionalidad Implementada

### Correo de Bienvenida
Cuando se crea un usuario, el sistema envía automáticamente un correo con:
- Nombre del usuario
- Correo electrónico (usuario)
- Contraseña (NIT para clientes, Identificación para técnicos)
- Rol en el sistema
- Enlace directo al login
- Recomendaciones de seguridad

### Archivos Modificados

1. **app/Mail/WelcomeUserMail.php** (NUEVO)
   - Clase Mailable para enviar correos
   - Implementa `ShouldQueue` para envío asíncrono

2. **resources/views/emails/welcome-user.blade.php** (NUEVO)
   - Plantilla del correo en formato Markdown
   - Diseño responsive

3. **app/Repositories/TecnicoRepository.php**
   - Envía correo al crear técnico
   - Contraseña: Identificación del técnico

4. **app/Repositories/ClientRepository.php**
   - Envía correo al crear cliente
   - Contraseña: NIT del cliente

## Configuración del Servidor de Correo

### Opción 1: Gmail (Desarrollo)

Agrega estas variables al archivo `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-correo@gmail.com
MAIL_PASSWORD=tu-contraseña-de-aplicacion
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu-correo@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Nota**: Para Gmail, debes crear una "Contraseña de aplicación" en tu cuenta de Google:
1. Ve a https://myaccount.google.com/security
2. Activa la verificación en 2 pasos
3. Busca "Contraseñas de aplicaciones"
4. Genera una contraseña para "Correo"
5. Usa esa contraseña en `MAIL_PASSWORD`

### Opción 2: Mailtrap (Desarrollo/Testing)

Para testing sin enviar correos reales:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu-usuario-mailtrap
MAIL_PASSWORD=tu-password-mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@tuempresa.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Opción 3: Servidor SMTP Corporativo

Si tienes un servidor SMTP propio:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.tuempresa.com
MAIL_PORT=587
MAIL_USERNAME=usuario@tuempresa.com
MAIL_PASSWORD=tu-contraseña
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@tuempresa.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Opción 4: Log (Desarrollo Local)

Para desarrollo sin servidor SMTP (los correos se guardan en logs):

```env
MAIL_MAILER=log
```

Los correos se guardarán en `storage/logs/laravel.log`

## Configuración de Colas (Opcional pero Recomendado)

Para mejorar el rendimiento, los correos se envían de forma asíncrona usando colas.

### 1. Configurar el Driver de Colas

En `.env`:

```env
QUEUE_CONNECTION=database
```

### 2. Crear la Tabla de Jobs

```bash
php artisan queue:table
php artisan migrate
```

### 3. Ejecutar el Worker

En desarrollo:
```bash
php artisan queue:work
```

En producción (usar Supervisor):
```bash
php artisan queue:work --daemon
```

## Probar el Sistema

### 1. Verificar Configuración

```bash
php artisan config:clear
php artisan config:cache
```

### 2. Crear un Usuario de Prueba

Crea un técnico o cliente desde la interfaz. El sistema debería:
- Crear el usuario
- Enviar el correo automáticamente
- Mostrar el mensaje en logs si usas `MAIL_MAILER=log`

### 3. Revisar Logs

```bash
tail -f storage/logs/laravel.log
```

## Contenido del Correo

El correo incluye:

### Para Clientes:
- Usuario: Email del cliente
- Contraseña: NIT del cliente
- Información sobre qué pueden hacer:
  - Consultar estado de solicitudes
  - Ver informes generados
  - Hacer seguimiento a equipos

### Para Técnicos:
- Usuario: Email del técnico
- Contraseña: Identificación del técnico
- Información sobre qué pueden hacer:
  - Revisar solicitudes asignadas
  - Consultar informes
  - Actualizar estado de servicios

## Solución de Problemas

### El correo no se envía

1. Verifica la configuración en `.env`
2. Ejecuta: `php artisan config:clear`
3. Revisa los logs: `storage/logs/laravel.log`
4. Verifica que el worker de colas esté corriendo: `php artisan queue:work`

### Error de autenticación SMTP

- Verifica usuario y contraseña
- Para Gmail, asegúrate de usar "Contraseña de aplicación"
- Verifica que el puerto y encryption sean correctos

### Los correos van a spam

- Configura registros SPF y DKIM en tu dominio
- Usa un servidor SMTP con buena reputación
- Agrega un dominio verificado en tu proveedor de correo

## Personalización

Para personalizar el correo, edita:
- **Plantilla**: `resources/views/emails/welcome-user.blade.php`
- **Lógica**: `app/Mail/WelcomeUserMail.php`

## Seguridad

- Las contraseñas se envían solo en el correo de creación
- Se recomienda al usuario cambiar su contraseña después del primer login
- Los correos se envían de forma asíncrona (no bloquean la aplicación)
- Las credenciales se hashean antes de guardarse en la base de datos
