<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Equipo;
use App\Models\Solicitud;
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
            ->map(function ($item) {
                return [
                    'usuario' => $item->user->name ?? 'Sin asignar',
                    'total' => $item->total,
                ];
            });

        // Solicitudes por estado (para gráfico de dona)
        $solicitudesPorEstado = Solicitud::select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->get()
            ->map(function ($item) {
                return [
                    'estado' => $item->estado,
                    'total' => $item->total,
                ];
            });

        // Solicitudes por prioridad
        $solicitudesPorPrioridad = Solicitud::select('prioridad', DB::raw('count(*) as total'))
            ->groupBy('prioridad')
            ->get()
            ->map(function ($item) {
                return [
                    'prioridad' => $item->prioridad,
                    'total' => $item->total,
                ];
            });

        // Solicitudes por tipo de equipo
        $solicitudesPorTipoEquipo = Solicitud::select('equipos.tipo_equipo', DB::raw('count(*) as total'))
            ->join('equipos', 'solicituds.equipo_id', '=', 'equipos.id')
            ->groupBy('equipos.tipo_equipo')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'tipo' => $item->tipo_equipo,
                    'total' => $item->total,
                ];
            });

        // Últimas solicitudes
        $ultimasSolicitudes = Solicitud::with(['client', 'user', 'equipo'])
            ->latest()
            ->limit(5)
            ->get();

        // Solicitudes programadas para el calendario
        $getColorByEstado = function ($estado) {
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
            ->map(function ($solicitud) use ($getColorByEstado) {
                return [
                    'id' => $solicitud->id,
                    'title' => '#'.$solicitud->numero_orden.' - '.($solicitud->client->enterprise_name ?? 'Sin cliente'),
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
                    ],
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
            ->map(function ($item) {
                return [
                    'mes' => $item->mes,
                    'total' => $item->total,
                ];
            });

        // Mantenimientos programados vs ejecutados por mes
        $mantenimientosProgramadosVsEjecutados = Solicitud::select(
            DB::raw("{$dateFormat} as mes"),
            DB::raw("SUM(CASE WHEN estado = 'Programada' THEN 1 ELSE 0 END) as programados"),
            DB::raw("SUM(CASE WHEN estado = 'Finalizada' AND (tipo_mantenimiento IS NOT NULL OR actividad = 'Mantenimiento Preventivo') THEN 1 ELSE 0 END) as ejecutados")
        )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->map(function ($item) {
                return [
                    'mes' => $item->mes,
                    'programados' => $item->programados,
                    'ejecutados' => $item->ejecutados,
                ];
            });

        // Mantenimientos del mes actual para el gauge
        $mantenimientosMesActual = Solicitud::select(
            DB::raw("SUM(CASE WHEN estado = 'Programada' OR estado = 'Finalizada' THEN 1 ELSE 0 END) as programados"),
            DB::raw("SUM(CASE WHEN estado = 'Finalizada' AND (tipo_mantenimiento IS NOT NULL OR actividad = 'Mantenimiento Preventivo') THEN 1 ELSE 0 END) as ejecutados")
        )
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->first();

        // Mantenimientos preventivos vs correctivos del mes actual
        $mantenimientosPorTipoMesActual = Solicitud::select(
            DB::raw("SUM(CASE WHEN tipo_mantenimiento = 'Mantenimiento Preventivo' OR actividad = 'Mantenimiento Preventivo' THEN 1 ELSE 0 END) as preventivos"),
            DB::raw("SUM(CASE WHEN tipo_mantenimiento = 'Mantenimiento Correctivo' OR (tipo_mantenimiento IS NULL AND actividad != 'Mantenimiento Preventivo') THEN 1 ELSE 0 END) as correctivos")
        )
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->first();

        // Emergencias atendidas por cliente (Top 10) - Total acumulado últimos 6 meses
        $emergenciasPorClienteMes = Solicitud::select(
            'clients.enterprise_name as cliente',
            DB::raw('count(*) as total')
        )
            ->join('clients', 'solicituds.client_id', '=', 'clients.id')
            ->where('solicituds.prioridad', 'Urgente')
            ->where('solicituds.created_at', '>=', now()->subMonths(6))
            ->groupBy('cliente')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'usuario' => $item->cliente,
                    'total' => $item->total,
                ];
            });

        // Solicitudes asignadas por técnico del mes actual
        $solicitudesPorTecnicoMensual = Solicitud::select(
            'users.name as tecnico',
            DB::raw('count(*) as total')
        )
            ->join('users', 'solicituds.user_id', '=', 'users.id')
            ->whereMonth('solicituds.created_at', now()->month)
            ->whereYear('solicituds.created_at', now()->year)
            ->groupBy('tecnico')
            ->orderBy('total', 'desc')
            ->limit(15)
            ->get()
            ->map(function ($item) {
                return [
                    'usuario' => $item->tecnico,
                    'total' => $item->total,
                ];
            });

        // Órdenes creadas vs finalizadas por mes (total creadas vs finalizadas)
        $ordenesAbiertasVsFinalizadas = Solicitud::select(
            DB::raw("{$dateFormat} as mes"),
            DB::raw('COUNT(*) as abiertas'),
            DB::raw("SUM(CASE WHEN estado = 'Finalizada' THEN 1 ELSE 0 END) as finalizadas")
        )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->map(function ($item) {
                return [
                    'mes' => $item->mes,
                    'abiertas' => $item->abiertas,
                    'finalizadas' => $item->finalizadas,
                ];
            });

        // Consolidado de tipo de solicitud de correctivos realizados en el mes
        $correctivosPorTipo = Solicitud::select(
            'actividad',
            DB::raw('count(*) as total')
        )
            ->where('tipo_mantenimiento', 'Mantenimiento Correctivo')
            ->orWhere(function ($query) {
                $query->whereNull('tipo_mantenimiento')
                    ->where('actividad', '!=', 'Mantenimiento Preventivo');
            })
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->groupBy('actividad')
            ->orderBy('total', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'actividad' => $item->actividad,
                    'total' => $item->total,
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
                'mantenimientosProgramadosVsEjecutados' => $mantenimientosProgramadosVsEjecutados,
                'mantenimientosMesActual' => [
                    'programados' => $mantenimientosMesActual->programados ?? 0,
                    'ejecutados' => $mantenimientosMesActual->ejecutados ?? 0,
                ],
                'mantenimientosPorTipoMesActual' => [
                    ['tipo' => 'Preventivos', 'total' => $mantenimientosPorTipoMesActual->preventivos ?? 0],
                    ['tipo' => 'Correctivos', 'total' => $mantenimientosPorTipoMesActual->correctivos ?? 0],
                ],
                'emergenciasPorClienteMes' => $emergenciasPorClienteMes,
                'solicitudesPorTecnicoMensual' => $solicitudesPorTecnicoMensual,
                'ordenesAbiertasVsFinalizadas' => $ordenesAbiertasVsFinalizadas,
                'correctivosPorTipo' => $correctivosPorTipo,
            ],
            'ultimasSolicitudes' => $ultimasSolicitudes,
            'solicitudesProgramadas' => $solicitudesProgramadas,
        ]);
    }
}
