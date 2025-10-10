<?php
namespace App\Repositories;

use App\Interfaces\ClientInterface;
use App\Models\Client;

class ClientRepository extends BaseRepository implements ClientInterface
{
    public function model(){
        return Client::class;
    }

}
