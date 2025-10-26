<?php

namespace App\Interfaces;

interface BaseInterface
{
    public function getAll($perPage = 15, $search = null);

    public function find(int $id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);

    public function getAllData();
}
