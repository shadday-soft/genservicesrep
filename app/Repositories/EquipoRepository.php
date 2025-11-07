<?php

namespace App\Repositories;

use App\Interfaces\EquipoInterface;
use App\Models\Actividad;
use App\Models\Equipo;
use App\Models\Solicitud;

class EquipoRepository extends BaseRepository implements EquipoInterface
{
    public function model()
    {
        return Equipo::class;
    }

    public function create(array $data)
    {
        $fecha_primer_mantenimiento = $data['fecha_primer_mantenimiento'];
        unset($data['fecha_primer_mantenimiento']);
        $equipo = parent::create($data);
        array_push($data['proximas_fechas_mantenimiento'], $fecha_primer_mantenimiento);
        if (isset($data['proximas_fechas_mantenimiento']) && is_array($data['proximas_fechas_mantenimiento'])) {
            $this->crearSolicitudesMantenimiento($equipo, $data['proximas_fechas_mantenimiento']);
        }
        return $equipo;
    }

    /**
     * Crea solicitudes de mantenimiento para cada fecha programada
     */
    protected function crearSolicitudesMantenimiento(Equipo $equipo, array $fechas): void
    {
        foreach ($fechas as $fecha) {
            Solicitud::create([
                'client_id' => $equipo->client_id,
                'sucursal_id' => $equipo->sucursal_id,
                'equipo_id' => $equipo->id,
                'user_id' => null,
                'fecha_programada' => null,
                'fecha_mantenimiento' => $fecha,
                'actividad' => 'Mantenimiento preventivo',
                'estado' => 'Nueva',
                'prioridad' => 'Normal',
            ]);
        }
    }

    public function getAll($perPage = 15, $search = null)
    {
        $query = Equipo::with(['client', 'sucursal']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre_equipo', 'like', "%{$search}%")
                    ->orWhere('tipo_equipo', 'like', "%{$search}%")
                    ->orWhere('modelo_equipo', 'like', "%{$search}%")
                    ->orWhere('marca_motor', 'like', "%{$search}%");
            });
        }

        if ($perPage === null || $perPage === 'all') {
            return $query->get();
        }

        return $query->latest()->paginate($perPage);
    }
}
