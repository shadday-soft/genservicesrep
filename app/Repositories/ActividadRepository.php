<?php

namespace App\Repositories;

use App\Interfaces\ActividadInterface;
use App\Models\Actividad;

class ActividadRepository extends BaseRepository implements ActividadInterface
{
    public function model()
    {
        return Actividad::class;
    }
}
