<?php

namespace App\Repositories;

use App\Interfaces\SucursalInterface;
use App\Models\Sucursal;

class SucursalRepository extends BaseRepository implements SucursalInterface
{
    public function model()
    {
        return Sucursal::class;
    }

    public function getAll($perPage = 15, $search = null)
    {
        $query = $this->model->with('client');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhere('contact_name', 'like', "%{$search}%");
            });
        }

        if ($perPage === null || $perPage === 'all') {
            return $query->get();
        }

        return $query->latest()->paginate($perPage);
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
