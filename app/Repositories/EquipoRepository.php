<?php
namespace App\Repositories;

use App\Interfaces\EquipoInterface;
use App\Models\Equipo;

class EquipoRepository extends BaseRepository implements EquipoInterface
{
    public function model(){
        return Equipo::class;
    }

}
