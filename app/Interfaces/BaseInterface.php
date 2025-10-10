<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface BaseInterface
{
    public function getAll(): Collection;

    public function find(int $id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);
}
