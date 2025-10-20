<?php
namespace App\Repositories;

use App\Interfaces\SolicitudInterface;
use App\Models\Solicitud;

class SolicitudRepository extends BaseRepository implements SolicitudInterface
{
    public function model(){
        return Solicitud::class;
    }

}
