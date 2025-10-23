<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\Client;
use App\Models\Equipo;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total de registros
        $totalSolicitudes = Solicitud::count();
        $totalClientes = Client::count();
        $totalEquipos = Equipo::count();
        $totalUsuarios = User::count();

        // Solicitudes por usuario (para gráfico de barras principal)
        $solicitudesPorUsuario = Solicitud::select('user_id', DB::raw('count(*) as total'))
            ->with('user:id,name')
            ->groupBy('user_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'usuario' => $item->user->name ?? 'Sin asignar',
                    'total' => $item->total
                ];
            });

        // Solicitudes por estado (para gráfico de dona)
        $solicitudesPorEstado = Solicitud::select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->get()
            ->map(function($item) {
                return [
                    'estado' => $item->estado,
                    'total' => $item->total
                ];
            });

        // Solicitudes por prioridad
        $solicitudesPorPrioridad = Solicitud::select('prioridad', DB::raw('count(*) as total'))
            ->groupBy('prioridad')
            ->get()
            ->map(function($item) {
                return [
                    'prioridad' => $item->prioridad,
                    'total' => $item->total
                ];
            });

        // Solicitudes por tipo de equipo
        $solicitudesPorTipoEquipo = Solicitud::select('equipos.tipo_equipo', DB::raw('count(*) as total'))
            ->join('equipos', 'solicituds.equipo_id', '=', 'equipos.id')
            ->groupBy('equipos.tipo_equipo')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'tipo' => $item->tipo_equipo,
                    'total' => $item->total
                ];
            });

        // Últimas solicitudes
        $ultimasSolicitudes = Solicitud::with(['client', 'user', 'equipo'])
            ->latest()
            ->limit(5)
            ->get();

        // Solicitudes programadas para el calendario
        $getColorByEstado = function($estado) {
            $colors = [
                'Nueva' => '#3b82f6',
                'Proceso' => '#eab308',
                'Revisión' => '#a855f7',
                'Finalizada' => '#22c55e',
                'Anulada' => '#ef4444',
                'Programada' => '#dc2626',
            ];
            return $colors[$estado] ?? '#6b7280';
        };

        $solicitudesProgramadas = Solicitud::with(['client', 'user', 'equipo'])
            ->whereNotNull('fecha_programada')
            ->get()
            ->map(function($solicitud) use ($getColorByEstado) {
                return [
                    'id' => $solicitud->id,
                    'title' => '#' . $solicitud->numero_orden . ' - ' . ($solicitud->client->enterprise_name ?? 'Sin cliente'),
                    'start' => $solicitud->fecha_programada,
                    'backgroundColor' => $getColorByEstado($solicitud->estado),
                    'borderColor' => $getColorByEstado($solicitud->estado),
                    'extendedProps' => [
                        'numero_orden' => $solicitud->numero_orden,
                        'cliente' => $solicitud->client->enterprise_name ?? 'N/A',
                        'equipo' => $solicitud->equipo->nombre_equipo ?? 'N/A',
                        'usuario' => $solicitud->user->name ?? 'N/A',
                        'estado' => $solicitud->estado,
                        'prioridad' => $solicitud->prioridad,
                    ]
                ];
            });

        $dbDriver = DB::getDriverName();
        $dateFormat = $dbDriver === 'pgsql' 
            ? "TO_CHAR(created_at, 'YYYY-MM')" 
            : "DATE_FORMAT(created_at, '%Y-%m')";
        
        $solicitudesPorMes = Solicitud::select(
                DB::raw("{$dateFormat} as mes"),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->map(function($item) {
                return [
                    'mes' => $item->mes,
                    'total' => $item->total
                ];
            });

        return inertia('Dashboard', [
            'stats' => [
                'totalSolicitudes' => $totalSolicitudes,
                'totalClientes' => $totalClientes,
                'totalEquipos' => $totalEquipos,
                'totalUsuarios' => $totalUsuarios,
            ],
            'charts' => [
                'solicitudesPorUsuario' => $solicitudesPorUsuario,
                'solicitudesPorEstado' => $solicitudesPorEstado,
                'solicitudesPorPrioridad' => $solicitudesPorPrioridad,
                'solicitudesPorTipoEquipo' => $solicitudesPorTipoEquipo,
                'solicitudesPorMes' => $solicitudesPorMes,
            ],
            'ultimasSolicitudes' => $ultimasSolicitudes,
            'solicitudesProgramadas' => $solicitudesProgramadas
        ]);
    }
}
