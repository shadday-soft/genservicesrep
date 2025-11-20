<?php

namespace App\Repositories;

use App\Interfaces\SolicitudInterface;
use App\Models\Solicitud;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SolicitudRepository extends BaseRepository implements SolicitudInterface
{
    private ClientRepository $clientRepository;

    public function __construct()
    {
        $this->clientRepository = new ClientRepository;
        $this->makeModel();
    }

    public function model()
    {
        return Solicitud::class;
    }

    public function getAllSolicitudes($perPage = 15, $search = null, $tipo = null, $estado = null)
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

        // Filtro por estado
        if ($estado) {
            $query->where('estado', $estado);
        }

        if ($user->role === 'Tecnico') {
            $query->where('user_id', $user->id);
        }

        if ($user->role === 'Cliente') {
            $client = $this->clientRepository->findByUserId($user->id);
            $query->where('client_id', $client->id);
        }

        if (isset($tipo)) {
            $query->where('tipo_mantenimiento', $tipo)->whereMonth('fecha_programada', Carbon::now()->month);
        }
        if (isset($tipo) && ! $estado) {
            $query->where('estado', 'Nueva');
        }

        $result = $query->latest()->paginate($perPage);

        // Mapear tipo_mantenimiento basado en actividad si no está definido
        $result->getCollection()->transform(function ($solicitud) {
            if (! $solicitud->tipo_mantenimiento) {
                $solicitud->tipo_mantenimiento = $solicitud->actividad === 'Mantenimiento Preventivo'
                    ? 'Mantenimiento Preventivo'
                    : 'Mantenimiento Correctivo';
            }

            return $solicitud;
        });

        return $result;
    }

    public function getSolicitudesParaCronograma()
    {
        $user = Auth::user();
        $query = Solicitud::with(['client', 'sucursal', 'equipo', 'user'])
            ->whereNotNull('fecha_programada')
            ->where('estado', 'Nueva')
            ->orderBy('fecha_programada', 'asc');

        // Filtrar por rol de usuario
        if ($user->role === 'Tecnico') {

            $query->where('user_id', $user->id);
        }

        if ($user->role === 'Cliente') {
            $client = $this->clientRepository->findByUserId($user->id);
            $query->where('client_id', $client->id);
        }

        return $query->get();
    }

    public function create(array $data): Solicitud
    {
        if ($data['orden_trabajo']) {
            $data['orden_trabajo'] = 'uploads/' . $data['orden_trabajo']->store('solicitus', 'public');
        }
        $solicitus = parent::create($data);

        return $solicitus;
    }

    public function update($id, array $data)
    {
        $solicitus = $this->find($id);

        if (isset($data['orden_trabajo']) && $data['orden_trabajo']) {
            if (is_object($data['orden_trabajo']) && method_exists($data['orden_trabajo'], 'store')) {

                if ($solicitus->orden_trabajo) {
                    Storage::disk('public')->delete($solicitus->orden_trabajo);
                }
                $data['orden_trabajo'] = 'uploads/' . $data['orden_trabajo']->store('solicitus', 'public');
            }
        }

        return parent::update($id, $data);
    }
}
