<?php

namespace App\Repositories;

use App\Interfaces\ClientInterface;
use App\Models\Client;

class ClientRepository extends BaseRepository implements ClientInterface
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository;
        $this->makeModel();
    }

    public function model()
    {
        return Client::class;
    }

    public function getAll($perPage = 15, $search = null)
    {
        $query = Client::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('enterprise_name', 'like', "%{$search}%")
                    ->orWhere('nit', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        if ($perPage === null || $perPage === 'all') {
            return $query->get();
        }

        return $query->latest()->paginate($perPage);
    }

    public function create(array $data)
    {
        $userData = [
            'name' => $data['enterprise_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['nit']),
        ];

        $user = $this->userRepository->create($userData);

        $data['user_id'] = $user->id;
        $clientData = $data;

        return parent::create($clientData);
    }



    public function update($id, array $data){
        $client = $this->find($id);
        if (!$client) {
            return null;
        }

        $userData = [
            'name' => $data['enterprise_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['nit']),
        ];

        $this->userRepository->update($client->user_id, $userData);

        $clientData = $data;

        return parent::update($id, $clientData);
    }
    public function findByUserId($userId)
    {
        return Client::where('user_id', $userId)->first();
    }
}
