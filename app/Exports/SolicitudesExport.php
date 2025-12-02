<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SolicitudesExport implements WithMultipleSheets
{
    use Exportable;

    protected $search;

    protected $tipo;

    protected $estado;

    protected $mes;

    protected $anio;

    public function __construct($search = null, $tipo = null, $estado = null, $mes = null, $anio = null)
    {
        $this->search = $search;
        $this->tipo = $tipo;
        $this->estado = $estado;
        $this->mes = $mes;
        $this->anio = $anio;
    }

    public function sheets(): array
    {
        return [
            new SolicitudesSheet($this->search, $this->tipo, $this->estado, $this->mes, $this->anio),
            new ConsolidadoSheet($this->search, $this->tipo, $this->estado, $this->mes, $this->anio),
        ];
    }
}
