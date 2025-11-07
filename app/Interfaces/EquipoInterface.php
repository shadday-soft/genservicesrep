<?php

namespace App\Interfaces;

use App\Models\Equipo;

interface EquipoInterface extends BaseInterface
{
    public function crearSolicitudesMantenimiento(Equipo $equipo, array $fechasMantenimiento): void;
}
