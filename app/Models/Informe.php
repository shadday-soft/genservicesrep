<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Informe extends Model
{
    /** @use HasFactory<\Database\Factories\InformeFactory> */
    use HasFactory;
    use HasUuids;

    protected $guarded = [];

}
