<?php

namespace App\Interfaces;

interface SolicitudInterface extends BaseInterface
{
    public function getAllSolicitudes($perPage = 15, $search = null, $tipo = null);

    public function getSolicitudesParaCronograma();
}
