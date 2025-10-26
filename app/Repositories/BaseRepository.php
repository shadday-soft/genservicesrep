<?php

namespace App\Repositories;

abstract class BaseRepository
{
    protected $model;

    abstract public function model();

    public function __construct()
    {
        $this->makeModel();
    }

    public function makeModel()
    {
        $this->model = app()->make($this->model());
    }

    public function getAll($perPage = null, $search = null)
    {
        if ($perPage === null || $perPage === 'all') {
            return $this->model->all();
        }

        return $this->model->paginate($perPage);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function update($id, array $data)
    {
        return $this->find($id)->update($data);
    }

    public function delete($id)
    {
        return $this->find($id)->delete();
    }

    public function findBy($field, $value)
    {
        return $this->model->where($field, $value)->first();
    }

    public function getAllData()
    {
        return $this->model->all();
    }
}
