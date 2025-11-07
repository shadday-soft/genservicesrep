<?php

namespace App\Repositories;

use App\Interfaces\SolicitudInterface;
use App\Models\Solicitud;
use Illuminate\Support\Facades\Auth;

class SolicitudRepository extends BaseRepository implements SolicitudInterface
{
    public function model()
    {
        return Solicitud::class;
    }

    public function getAllSolicitudes($perPage = 15, $search = null, $tipo = null)
    {
        $user = Auth::user();
        $query = Solicitud::with(['client', 'sucursal', 'equipo', 'user']);

        // Aplicar búsqueda si existe
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('numero_orden', 'like', "%{$search}%")
                    ->orWhere('detalles', 'like', "%{$search}%")
                    ->orWhere('estado', 'like', "%{$search}%")
                    ->orWhere('prioridad', 'like', "%{$search}%")
                    ->orWhere('telefono', 'like', "%{$search}%")
                    ->orWhere('mail', 'like', "%{$search}%")
                    ->orWhere('quien_solicita', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($q) use ($search) {
                        $q->where('enterprise_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('sucursal', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('equipo', function ($q) use ($search) {
                        $q->where('nombre_equipo', 'like', "%{$search}%")
                            ->orWhere('tipo_equipo', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }
        if ($user->role === 'Tecnico') {
            $query->where('user_id', $user->id);
        }

        if ($user->role === 'Cliente') {
            $query->where('client_id', $user->id);
        }

        if ($tipo == 'Mantenimiento Preventivo') {
            $query->where('actividad', $tipo);
        } elseif (isset($tipo)) {
            $query->where('actividad', '!=', 'Mantenimiento Preventivo');
        }

        if (isset($tipo)) {
            $query->where('estado', 'Nueva');
        }
        // dd($tipo);

        return $query->latest()->paginate($perPage);
    }

    public function getSolicitudesParaCronograma()
    {
        $user = Auth::user();
        $query = Solicitud::with(['client', 'sucursal', 'equipo', 'user'])
            ->whereNotNull('fecha_programada')
            ->orderBy('fecha_programada', 'asc');

        // Filtrar por rol de usuario
        if ($user->role === 'Tecnico') {
            $query->where('user_id', $user->id);
        }

        if ($user->role === 'Cliente') {
            $query->where('client_id', $user->id);
        }

        return $query->get();
    }
}
