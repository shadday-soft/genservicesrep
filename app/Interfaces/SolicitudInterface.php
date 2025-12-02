<?php

namespace App\Interfaces;

interface SolicitudInterface extends BaseInterface
{
    public function getAllSolicitudes($perPage = 15, $search = null, $tipo = null, $estado = null, $mes = null, $anio = null);

    public function getSolicitudesParaCronograma();
}
