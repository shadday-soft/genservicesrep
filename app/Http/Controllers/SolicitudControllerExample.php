<?php

namespace App\Http\Controllers;

use App\Repositories\SolicitudRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

/**
 * Controlador de ejemplo mostrando cómo usar el SolicitudRepository optimizado
 */
class SolicitudControllerExample extends Controller
{
    protected SolicitudRepository $solicitudRepository;

    public function __construct(SolicitudRepository $solicitudRepository)
    {
        $this->solicitudRepository = $solicitudRepository;
    }

    /**
     * Listar solicitudes con búsqueda y paginación optimizada
     *
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        // Obtener parámetros de búsqueda
        $search = $request->get('search', null);
        $perPage = $request->get('per_page', 15);

        // Obtener solicitudes paginadas con búsqueda optimizada
        // Esta consulta usa:
        // - Solo 2 queries SQL (total + datos)
        // - JOINs eficientes
        // - Paginación a nivel de BD
        // - Índices para búsquedas rápidas
        $solicitudes = $this->solicitudRepository->getAll($perPage, $search);

        return Inertia::render('Solicitudes/Index', [
            'solicitudes' => $solicitudes,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    /**
     * Ejemplo de uso en API REST
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex(Request $request)
    {
        $search = $request->get('search', null);
        $perPage = $request->get('per_page', 15);

        $solicitudes = $this->solicitudRepository->getAll($perPage, $search);

        return response()->json([
            'data' => $solicitudes->items(),
            'meta' => [
                'current_page' => $solicitudes->currentPage(),
                'last_page' => $solicitudes->lastPage(),
                'per_page' => $solicitudes->perPage(),
                'total' => $solicitudes->total(),
                'from' => $solicitudes->firstItem(),
                'to' => $solicitudes->lastItem(),
            ],
            'links' => [
                'first' => $solicitudes->url(1),
                'last' => $solicitudes->url($solicitudes->lastPage()),
                'prev' => $solicitudes->previousPageUrl(),
                'next' => $solicitudes->nextPageUrl(),
            ],
        ]);
    }

    /**
     * Ejemplo con estadísticas de rendimiento
     *
     * @return \Inertia\Response
     */
    public function indexWithStats(Request $request)
    {
        $search = $request->get('search', null);
        $perPage = $request->get('per_page', 15);

        // Medir tiempo de ejecución
        $startTime = microtime(true);

        DB::enableQueryLog();
        $solicitudes = $this->solicitudRepository->getAll($perPage, $search);
        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        $executionTime = (microtime(true) - $startTime) * 1000;

        return Inertia::render('Solicitudes/Index', [
            'solicitudes' => $solicitudes,
            'filters' => [
                'search' => $search,
            ],
            'stats' => [
                'execution_time_ms' => round($executionTime, 2),
                'queries_count' => count($queries),
                'total_records' => $solicitudes->total(),
            ],
        ]);
    }

    /**
     * Mostrar detalles de una solicitud
     *
     * @return \Inertia\Response
     */
    public function show(string $id)
    {
        $solicitud = $this->solicitudRepository->find($id);

        // Cargar relaciones si no están precargadas
        $solicitud->load(['client', 'sucursal', 'equipo', 'user']);

        return Inertia::render('Solicitudes/Show', [
            'solicitud' => $solicitud,
        ]);
    }

    /**
     * Crear nueva solicitud
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|uuid|exists:clients,id',
            'sucursal_id' => 'required|uuid|exists:sucursals,id',
            'equipo_id' => 'required|uuid|exists:equipos,id',
            'user_id' => 'required|exists:users,id',
            'detalles' => 'nullable|string',
            'estado' => 'required|in:Nueva,Proceso,Revisión,Finalizada,Anulada,Programada',
            'prioridad' => 'required|in:Normal,Intermedio,Urgente',
            'telefono' => 'nullable|string|max:255',
            'mail' => 'nullable|email|max:255',
            'quien_solicita' => 'nullable|string|max:255',
            'actividad' => 'required|string|max:255',
            'fecha_programada' => 'nullable|date',
        ]);

        $solicitud = $this->solicitudRepository->create($validated);

        return redirect()
            ->route('solicitudes.show', $solicitud->id)
            ->with('success', 'Solicitud creada exitosamente');
    }

    /**
     * Actualizar solicitud
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'estado' => 'sometimes|in:Nueva,Proceso,Revisión,Finalizada,Anulada,Programada',
            'prioridad' => 'sometimes|in:Normal,Intermedio,Urgente',
            'detalles' => 'nullable|string',
            'telefono' => 'nullable|string|max:255',
            'mail' => 'nullable|email|max:255',
        ]);

        $this->solicitudRepository->update($id, $validated);

        return redirect()
            ->route('solicitudes.show', $id)
            ->with('success', 'Solicitud actualizada exitosamente');
    }

    /**
     * Eliminar solicitud
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        $this->solicitudRepository->delete($id);

        return redirect()
            ->route('solicitudes.index')
            ->with('success', 'Solicitud eliminada exitosamente');
    }

    /**
     * Búsqueda avanzada (ejemplo)
     *
     * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
     */
    public function search(Request $request)
    {
        $search = $request->get('q', null);
        $perPage = 15;

        if (! $search) {
            return redirect()->route('solicitudes.index');
        }

        $solicitudes = $this->solicitudRepository->getAll($perPage, $search);

        return Inertia::render('Solicitudes/SearchResults', [
            'solicitudes' => $solicitudes,
            'query' => $search,
            'total_results' => $solicitudes->total(),
        ]);
    }
}
