<?php

namespace App\Repositories;

use App\Interfaces\UserInterface;
use App\Models\User;

class UserRepository extends BaseRepository implements UserInterface
{
    public function model()
    {
        return User::class;
    }

    public function getAll($perPage = null, $search = null)
    {
        // dd($this->model->get());
        return $this->model->where('role', 'LIKE' ,$search)->get();

    }
}
