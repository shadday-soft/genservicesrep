<?php

namespace App\Repositories;

use App\Interfaces\EquipoInterface;
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
        // Extraer datos que NO van en el equipo
        $tecnicoId = $data['tecnico_id'] ?? null;
        $quienSolicita = $data['quien_solicita'] ?? null;
        $ordenCompra = $data['orden_compra'] ?? null;

        // Remover campos que no pertenecen a la tabla equipos
        unset($data['fecha_primer_mantenimiento']);
        unset($data['tecnico_id']);
        unset($data['quien_solicita']);
        unset($data['orden_compra']);

        $equipo = parent::create($data);

        // Crear solicitudes de mantenimiento si hay fechas programadas
        if (isset($data['proximas_fechas_mantenimiento']) && is_array($data['proximas_fechas_mantenimiento'])) {
            $this->crearSolicitudesMantenimiento(
                $equipo,
                $data['proximas_fechas_mantenimiento'],
                $tecnicoId,
                $quienSolicita,
                $ordenCompra
            );
        }

        return $equipo;
    }

    /**
     * Crea solicitudes de mantenimiento para cada fecha programada
     */
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

    /**
     * Crea solicitudes de mantenimiento preventivo basadas en las fechas programadas
     */
    public function crearSolicitudesMantenimiento(
        Equipo $equipo,
        array $fechasMantenimiento,
        ?int $tecnicoId = null,
        ?string $quienSolicita = null,
        $ordenCompra = null
    ): void {
        if (empty($fechasMantenimiento)) {
            return;
        }

        // Guardar el archivo de orden de compra si existe
        $ordenTrabajoPath = null;
        if ($ordenCompra) {
            $ordenTrabajoPath = 'uploads/'.$ordenCompra->store('solicituds', 'public');
        }

        foreach ($fechasMantenimiento as $fecha) {
            Solicitud::create([
                'client_id' => $equipo->client_id,
                'sucursal_id' => $equipo->sucursal_id,
                'equipo_id' => $equipo->id,
                'user_id' => $tecnicoId,
                'quien_solicita' => $quienSolicita,
                'orden_trabajo' => $ordenTrabajoPath,
                'fecha_programada' => $fecha,
                'fecha_mantenimiento' => $fecha,
                'actividad' => 'Mantenimiento preventivo',
                'estado' => 'Nueva',
                'prioridad' => 'Normal',
                'detalles' => 'Mantenimiento preventivo programado automáticamente según periodicidad: '.$equipo->periodicidad,
            ]);
        }
    }
}
