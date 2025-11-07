<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    /** @use HasFactory<\Database\Factories\EquipoFactory> */
    use HasFactory;

    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'proximas_fechas_mantenimiento' => 'array',
        'fecha_primer_mantenimiento' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }
}
