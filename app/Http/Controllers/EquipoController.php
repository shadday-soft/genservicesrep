<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEquipoRequest;
use App\Http\Requests\UpdateEquipoRequest;
use App\Interfaces\EquipoInterface;
use App\Models\Equipo;
use Illuminate\Support\Facades\DB;

class EquipoController extends Controller
{
    public function __construct(
        private EquipoInterface $repository,
    ) {}

    public function index()
    {
        $search = request('search');
        $perPage = request('per_page', 15);

        $equipos = $this->repository->getAll($perPage, $search);

        if (request()->wantsJson()) {
            return response()->json([
                'equipos' => $this->repository->getAllData(),
            ]);
        }

        return inertia('Equipos/Index', [
            'equipos' => $equipos,
            'filters' => [
                'search' => $search,
                'per_page' => $perPage,
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
    public function store(StoreEquipoRequest $request)
    {
        
            DB::beginTransaction();
            $equipo = $this->repository->create($request->validated());

            // Crear solicitudes de mantenimiento si hay fechas programadas
            if (! empty($request->input('proximas_fechas_mantenimiento'))) {
                $this->repository->crearSolicitudesMantenimiento(
                    $equipo,
                    $request->input('proximas_fechas_mantenimiento')
                );
            }

            DB::commit();

            return back()->with('status', 'Equipo create successfully');
       
    }

    /**
     * Display the specified resource.
     */
    public function show(Equipo $equipo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Equipo $equipo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEquipoRequest $request, Equipo $equipo)
    {
        try {
            DB::beginTransaction();
            $this->repository->update($equipo->id, $request->validated());
            DB::commit();

            return back()->with('status', 'Equipo updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withError('errors', 'Action no Disabled');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipo $equipo)
    {
        try {
            DB::beginTransaction();
            $this->repository->delete($equipo->id);
            DB::commit();

            return back()->with('status', 'Equipo delete successfully');
        } catch (\Exception $e) {
            return back()->withError('errors', 'Action no Disabled');
        }
    }
}
