<?php

namespace App\Repositories;

use App\Interfaces\SucursalInterface;
use App\Models\Sucursal;
use Illuminate\Support\Facades\Storage;

class SucursalRepository extends BaseRepository implements SucursalInterface
{
    public function model()
    {
        return Sucursal::class;
    }

    public function getAll()
    {
        return $this->model->with('client')->get();
    }

    public function create(array $data): Sucursal
    {
        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('sucursals', 'public');
        }
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $sucursal = $this->model->findOrFail($id);
        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('sucursals', 'public');
        }
        $sucursal->update($data);
        return $sucursal;
    }
}
