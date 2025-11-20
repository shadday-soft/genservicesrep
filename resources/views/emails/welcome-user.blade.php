<x-mail::message>
# ¡Bienvenido a {{ config('app.name') }}!

Hola **{{ $userName }}**,

Tu cuenta ha sido creada exitosamente como **{{ $userRole }}** en nuestra plataforma de gestión de servicios.

## Credenciales de Acceso

A continuación encontrarás tus credenciales para acceder a la plataforma:

<x-mail::panel>
**Correo electrónico:** {{ $userEmail }}  
**Contraseña:** {{ $userPassword }}
</x-mail::panel>

## ¿Qué puedes hacer?

@if($userRole === 'Cliente')
- Consultar el estado de tus solicitudes de servicio
- Ver los informes generados de los mantenimientos realizados
- Realizar seguimiento a tus equipos
@elseif($userRole === 'Tecnico')
- Revisar las solicitudes asignadas
- Consultar informes de trabajos realizados
- Actualizar el estado de tus servicios
@endif

<x-mail::button :url="$loginUrl">
Acceder a la Plataforma
</x-mail::button>

## Recomendaciones de Seguridad

Si tienes alguna pregunta o necesitas ayuda, no dudes en contactarnos.

Saludos cordiales,<br>
Gen Services
</x-mail::message>
