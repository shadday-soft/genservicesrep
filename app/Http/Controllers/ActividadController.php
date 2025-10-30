<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActividadRequest;
use App\Http\Requests\UpdateActividadRequest;
use App\Interfaces\ActividadInterface;
use App\Models\Actividad;
use Illuminate\Support\Facades\DB;

class ActividadController extends Controller
{
    public function __construct(
        private ActividadInterface $repository,
    ) {}

    public function index()
    {
        $search = request('search');
        $perPage = request('per_page', 15);

        $actividades = $this->repository->getAll($perPage, $search);

        if (request()->wantsJson()) {
            return response()->json([
                'actividads' => $this->repository->getAllData(),
            ]);
        }

        return inertia('Actividads/Index', [
            'actividads' => $actividades,
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
    public function store(StoreActividadRequest $request)
    {
        // try{
        //     DB::beginTransaction();
        $this->repository->create($request->validated());
        //     DB::commit();
        //     return back()->with('status', 'Actividad create successfully');
        // }catch(\Exception $e){
        //     return back()->withError('errors', 'Action no Disabled');
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(Actividad $actividad)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Actividad $actividad)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActividadRequest $request, Actividad $actividad)
    {
        try {
            DB::beginTransaction();
            $this->repository->update($actividad->id, $request->validated());
            DB::commit();

            return back()->with('status', 'Actividad updated successfully');
        } catch (\Exception $e) {
            return back()->withError('errors', 'Action no Disabled');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($actividad)
    {
        try {
            DB::beginTransaction();
            $this->repository->delete($actividad);
            DB::commit();

            return back()->with('status', 'Actividad delete successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error deleting Actividad: ' . $e->getMessage()]);
        }
    }
}
