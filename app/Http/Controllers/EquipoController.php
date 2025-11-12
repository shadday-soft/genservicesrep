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
            $validatedData = $request->validated();
            $this->repository->update($equipo->id, $validatedData);
            DB::commit();

            return back()->with('status', 'Equipo updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors([
                'errors' => 'Action not completed',
                'exception' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipo $equipo)
    {
        DB::beginTransaction();
        try {
            $this->repository->delete($equipo->id);
            DB::commit();
        } catch (\Exception $e) {
            // dd($e->getMessage());
            DB::rollBack();

            return back()->withErrors([
                'errors' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Renueva el contrato: recibe un array de fechas y crea solicitudes de mantenimiento
     */
    public function renew(\Illuminate\Http\Request $request, Equipo $equipo)
    {
        $data = $request->validate([
            'fechas' => ['required', 'array'],
            'fechas.*' => ['required', 'date'],
        ]);

        try {
            DB::beginTransaction();
            $this->repository->crearSolicitudesMantenimiento($equipo, $data['fechas']);
            DB::commit();

            return back()->with('status', 'Renovación procesada correctamente, solicitudes creadas');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors([
                'errors' => $e->getMessage(),
            ]);
        }
    }
}
