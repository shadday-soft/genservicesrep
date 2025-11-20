<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    /** @use HasFactory<\Database\Factories\SolicitudFactory> */
    use HasFactory;

    use HasUuids;

    protected $guarded = [];

    protected $appends = ['tipo_mantenimiento_display'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor para mostrar el tipo de mantenimiento
     */
    public function getTipoMantenimientoDisplayAttribute(): string
    {
        if ($this->tipo_mantenimiento) {
            return $this->tipo_mantenimiento;
        }

        return $this->actividad === 'Mantenimiento Preventivo'
            ? 'Mantenimiento Preventivo'
            : 'Mantenimiento Correctivo';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($solicitud) {
            $maxId = self::max('numero_orden') ?? 0;
            // dd( $maxId );
            $nuevoNumero = $maxId + 1;
            $solicitud->numero_orden = str_pad($nuevoNumero, 4, '0', STR_PAD_LEFT);
        });
    }
}
