<?php

namespace App\Exports;

use App\Models\Solicitud;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SolicitudesSheet implements FromQuery, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $search;
    protected $tipo;
    protected $estado;

    public function __construct($search = null, $tipo = null, $estado = null)
    {
        $this->search = $search;
        $this->tipo = $tipo;
        $this->estado = $estado;
    }

    public function query()
    {
        $user = Auth::user();
        $query = Solicitud::with(['client', 'sucursal', 'equipo', 'user']);

        // Aplicar búsqueda si existe
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

        // Aplicar filtro por tipo si existe
        if ($this->tipo) {
            $query->where('tipo_mantenimiento', $this->tipo);
        }

        // Aplicar filtro por estado si existe
        if ($this->estado) {
            $query->where('estado', $this->estado);
        }

        // Si es cliente, solo ver sus solicitudes
        if ($user && $user->role === 'Cliente') {
            $query->whereHas('client.users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'Número de Orden',
            'Cliente',
            'NIT',
            'Sucursal',
            'Dirección Sucursal',
            'Teléfono Sucursal',
            'Email Sucursal',
            'Contacto Sucursal',
            'Equipo',
            'Tipo Equipo',
            'Potencia',
            'Modelo Equipo',
            'Modelo Motor',
            'Serie Equipo',
            'Serie Motor',
            'Técnico Asignado',
            'Actividad',
            'Tipo Mantenimiento',
            'Estado',
            'Prioridad',
            'Detalles',
            'Teléfono Contacto',
            'Email Contacto',
            'Quien Solicita',
            'Ubicación',
            'Fecha Programada',
            'Fecha Creación',
            'Informe Generado',
            'Razón Cancelación',
        ];
    }

    public function map($solicitud): array
    {
        return [
            $solicitud->numero_orden,
            $solicitud->client->enterprise_name ?? 'N/A',
            $solicitud->client->nit ?? 'N/A',
            $solicitud->sucursal->name ?? 'N/A',
            $solicitud->sucursal->address ?? 'N/A',
            $solicitud->sucursal->phone_number ?? 'N/A',
            $solicitud->sucursal->email ?? 'N/A',
            $solicitud->sucursal->contact_name ?? 'N/A',
            $solicitud->equipo->nombre_equipo ?? 'N/A',
            $solicitud->equipo->tipo_equipo ?? 'N/A',
            $solicitud->equipo->potencia ?? 'N/A',
            $solicitud->equipo->modelo_equipo ?? 'N/A',
            $solicitud->equipo->modelo_motor ?? 'N/A',
            $solicitud->equipo->serie_equipo ?? 'N/A',
            $solicitud->equipo->serie_motor ?? 'N/A',
            $solicitud->user->name ?? 'Sin asignar',
            $solicitud->actividad ?? 'N/A',
            $solicitud->tipo_mantenimiento ?? 'N/A',
            $solicitud->estado ?? 'N/A',
            $solicitud->prioridad ?? 'Normal',
            strip_tags($solicitud->detalles ?? ''),
            $solicitud->telefono ?? 'N/A',
            $solicitud->mail ?? 'N/A',
            $solicitud->quien_solicita ?? 'N/A',
            $solicitud->ubicacion ?? 'N/A',
            $solicitud->fecha_programada ? \Carbon\Carbon::parse($solicitud->fecha_programada)->format('Y-m-d H:i') : 'N/A',
            $solicitud->created_at ? $solicitud->created_at->format('Y-m-d H:i') : 'N/A',
            $solicitud->informe_generado ? 'Sí' : 'No',
            $solicitud->razon_cancelacion ?? '',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function title(): string
    {
        return 'Solicitudes';
    }
}
