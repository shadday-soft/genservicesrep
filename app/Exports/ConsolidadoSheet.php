<?php

namespace App\Exports;

use App\Models\Solicitud;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ConsolidadoSheet implements FromCollection, WithHeadings, WithStyles, WithTitle
{
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

    public function collection(): Collection
    {
        $user = Auth::user();
        $query = Solicitud::with(['client', 'sucursal', 'equipo']);

        // Aplicar filtros igual que en la hoja de solicitudes
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('numero_orden', 'like', "%{$this->search}%")
                    ->orWhere('detalles', 'like', "%{$this->search}%")
                    ->orWhere('estado', 'like', "%{$this->search}%")
                    ->orWhere('actividad', 'like', "%{$this->search}%")
                    ->orWhereHas('client', function ($q) {
                        $q->where('enterprise_name', 'like', "%{$this->search}%");
                    })
                    ->orWhereHas('sucursal', function ($q) {
                        $q->where('name', 'like', "%{$this->search}%");
                    })
                    ->orWhereHas('equipo', function ($q) {
                        $q->where('nombre_equipo', 'like', "%{$this->search}%");
                    });
            });
        }

        if ($this->tipo) {
            $query->where('tipo_mantenimiento', $this->tipo);
        }

        if ($this->estado) {
            $query->where('estado', $this->estado);
        }

        if ($this->mes) {
            $query->whereMonth('fecha_programada', $this->mes);
        }

        if ($this->anio) {
            $query->whereYear('fecha_programada', $this->anio);
        }

        if ($user && $user->role === 'Cliente') {
            $query->whereHas('client.users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }

        // Obtener consolidado por cliente y sucursal
        $consolidado = $query->get()
            ->groupBy(function ($solicitud) {
                return ($solicitud->client->enterprise_name ?? 'Sin Cliente').' - '.($solicitud->sucursal->name ?? 'Sin Sucursal');
            })
            ->map(function ($group, $key) {
                $clienteSucursal = explode(' - ', $key);
                $totalSolicitudes = $group->count();
                $porEstado = $group->countBy('estado');
                $porPrioridad = $group->countBy('prioridad');
                $conInforme = $group->where('informe_generado', true)->count();

                return [
                    'cliente' => $clienteSucursal[0] ?? 'N/A',
                    'sucursal' => $clienteSucursal[1] ?? 'N/A',
                    'total_solicitudes' => $totalSolicitudes,
                    'nuevas' => $porEstado['Nueva'] ?? 0,
                    'en_proceso' => $porEstado['Proceso'] ?? 0,
                    'finalizadas' => $porEstado['Finalizada'] ?? 0,
                    'anuladas' => $porEstado['Anulada'] ?? 0,
                    'normal' => $porPrioridad['Normal'] ?? 0,
                    'intermedio' => $porPrioridad['Intermedio'] ?? 0,
                    'urgente' => $porPrioridad['Urgente'] ?? 0,
                    'con_informe' => $conInforme,
                    'sin_informe' => $totalSolicitudes - $conInforme,
                    'primer_solicitud' => $group->min('created_at') ? \Carbon\Carbon::parse($group->min('created_at'))->format('Y-m-d') : 'N/A',
                    'ultima_solicitud' => $group->max('created_at') ? \Carbon\Carbon::parse($group->max('created_at'))->format('Y-m-d') : 'N/A',
                ];
            })
            ->values();

        return $consolidado;
    }

    public function headings(): array
    {
        return [
            'Cliente',
            'Sucursal',
            'Total Solicitudes',
            'Nuevas',
            'En Proceso',
            'Finalizadas',
            'Anuladas',
            'Prioridad Normal',
            'Prioridad Intermedio',
            'Prioridad Urgente',
            'Con Informe',
            'Sin Informe',
            'Primera Solicitud',
            'Última Solicitud',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2E8F0'],
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'Consolidado';
    }
}
