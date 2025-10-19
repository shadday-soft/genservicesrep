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
    )
    {
    }

    public function index()
    {
        $equipos = $this->repository->getAll();

        if (request()->wantsJson()) {
            return response()->json([
                'equipos' => $equipos
            ]);
        }
        return inertia('Equipos/Index', compact('equipos'));
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
        try{
            DB::beginTransaction();
            $this->repository->create($request->validated());
            DB::commit();
            return back()->with('status', 'Equipo create successfully');
        }catch(\Exception $e){
            return back()->withError('errors', 'Action no Disabled');
        }
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
          try{
            DB::beginTransaction();
            $this->repository->update($equipo->id,$request->validated());
            DB::commit();
            return back()->with('status', 'Equipo updated successfully');
        }catch(\Exception $e){
            return back()->withError('errors', 'Action no Disabled');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipo $equipo)
    {
        try{
            DB::beginTransaction();
            $this->repository->delete($equipo->id);
            DB::commit();
            return back()->with('status', 'Equipo delete successfully');
        }catch(\Exception $e){
            return back()->withError('errors', 'Action no Disabled');
        }
    }
}
