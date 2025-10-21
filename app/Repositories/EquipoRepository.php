<?php
namespace App\Repositories;

use App\Interfaces\EquipoInterface;
use App\Models\Equipo;

class EquipoRepository extends BaseRepository implements EquipoInterface
{
    public function model(){
        return Equipo::class;
    }

    public function getAll($perPage = 15, $search = null)
    {
        $query = Equipo::with(['client', 'sucursal']);
        
        if ($search) {
            $query->where(function($q) use ($search) {
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
