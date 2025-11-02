<?php

namespace App\Repositories;

use App\Interfaces\SolicitudInterface;
use App\Models\Solicitud;

class SolicitudRepository extends BaseRepository implements SolicitudInterface
{
    public function model()
    {
        return Solicitud::class;
    }

    public function getAll($perPage = 15, $search = null)
    {
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
        if (auth()->user()->role === 'Tecnico') {
            $query->where('user_id', auth()->user()->id);
        }
        return $query->latest()->paginate($perPage);
    }
}
