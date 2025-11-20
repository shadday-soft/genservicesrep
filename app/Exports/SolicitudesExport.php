<?php

namespace App\Exports;

use App\Models\Solicitud;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SolicitudesExport implements WithMultipleSheets
{
    use Exportable;

    protected $search;
    protected $tipo;
    protected $estado;

    public function __construct($search = null, $tipo = null, $estado = null)
    {
        $this->search = $search;
        $this->tipo = $tipo;
        $this->estado = $estado;
    }

    public function sheets(): array
    {
        return [
            new SolicitudesSheet($this->search, $this->tipo, $this->estado),
            new ConsolidadoSheet($this->search, $this->tipo, $this->estado),
        ];
    }
}
