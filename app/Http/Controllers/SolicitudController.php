<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSolicitudRequest;
use App\Http\Requests\UpdateSolicitudRequest;
use App\Interfaces\SolicitudInterface;
use App\Models\Solicitud;
use Illuminate\Support\Facades\DB;

class SolicitudController extends Controller
{
    public function __construct(
        private SolicitudInterface $repository,
    ) {}

    public function index()
    {
        $search = request('search');
        $perPage = request('per_page', 15);
        $tipoSolicitudes = request('tipo');

        $solicituds = $this->repository->getAllSolicitudes($perPage, $search, $tipoSolicitudes);

        if (request()->wantsJson()) {
            return response()->json([
                'solicituds' => $this->repository->getAllData(),
            ], 200);
        }

        return inertia('Solicituds/Index', [
            'solicituds' => $solicituds,
            'filters' => [
                'search' => $search,
                'per_page' => $perPage,
                'tipo' => $tipoSolicitudes,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSolicitudRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->repository->create($request->validated());
            DB::commit();

            return back()->with('status', 'Solicitud create successfully');
        } catch (\Exception $e) {
            return back()->withError('errors', 'Action no Disabled');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Solicitud $solicitud)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Solicitud $solicitud)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSolicitudRequest $request, Solicitud $solicitud)
    {

        DB::beginTransaction();
        $this->repository->update($solicitud->id, $request->validated());
        DB::commit();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Solicitud $solicitud)
    {
        try {
            DB::beginTransaction();
            $this->repository->delete($solicitud->id);
            DB::commit();

            return back()->with('status', 'Solicitud delete successfully');
        } catch (\Exception $e) {
            return back()->withError('errors', 'Action no Disabled');
        }
    }

    /**
     * Muestra el cronograma tipo Gantt de las solicitudes
     */
    public function cronograma()
    {
        $solicituds = $this->repository->getSolicitudesParaCronograma();

        return inertia('Solicituds/Cronogram', [
            'solicituds' => $solicituds,
        ]);
    }
}
