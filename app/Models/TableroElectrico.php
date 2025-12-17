<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TableroElectrico extends Model
{
    

    protected $table = 'tableros_electricos';

    protected $guarded = [];

    protected $casts = [
        'fecha_solicitud' => 'date',
    ];

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(Solicitud::class);
    }
}
