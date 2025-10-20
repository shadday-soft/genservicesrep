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
    )
    {
    }

    public function index()
    {
        $solicituds = $this->repository->getAll();
        if(request()->wantsJson()){
            return response()->json(['solicituds'=>$solicituds],200);
        }
        return inertia('Solicituds/Index', compact('solicituds'));

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
        try{
            DB::beginTransaction();
            $this->repository->create($request->validated());
            DB::commit();
            return back()->with('status', 'Solicitud create successfully');
        }catch(\Exception $e){
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
          try{
            DB::beginTransaction();
            $this->repository->update($solicitud->id,$request->validated());
            DB::commit();
            return back()->with('status', 'Solicitud updated successfully');
        }catch(\Exception $e){
            return back()->withError('errors', 'Action no Disabled');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Solicitud $solicitud)
    {
        try{
            DB::beginTransaction();
            $this->repository->delete($solicitud->id);
            DB::commit();
            return back()->with('status', 'Solicitud delete successfully');
        }catch(\Exception $e){
            return back()->withError('errors', 'Action no Disabled');
        }
    }
}
