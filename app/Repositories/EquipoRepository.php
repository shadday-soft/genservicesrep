<?php
namespace App\Repositories;

use App\Interfaces\EquipoInterface;
use App\Models\Equipo;

class EquipoRepository extends BaseRepository implements EquipoInterface
{
    public function model(){
        return Equipo::class;
    }

    public function getAll()
    {
        return Equipo::with(['client', 'sucursal'])->get();
    }

}
