<?php

namespace App\Repositories;

use App\Interfaces\ClientInterface;
use App\Models\Client;

class ClientRepository extends BaseRepository implements ClientInterface
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->makeModel();
    }
    public function model()
    {
        return Client::class;
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
}
