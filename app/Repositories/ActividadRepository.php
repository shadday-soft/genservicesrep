<?php

namespace App\Repositories;

use App\Interfaces\ActividadInterface;
use App\Models\Actividad;

class ActividadRepository extends BaseRepository implements ActividadInterface
{
    public function __construct(private SolicitudRepository $solicitudRepository)
    {
        parent::__construct();
    }

    public function model()
    {
        return Actividad::class;
    }

    public function delete($id)
    {
        $actividad = Actividad::findOrFail($id);
        if ($this->solicitudRepository->findBy('actividad', $actividad->nombre)) {
            $actividad->active = false;
            $actividad->save();
        } else {
            $actividad->delete();
        }
    }
}
