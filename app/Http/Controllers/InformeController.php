<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInformeRequest;
use App\Http\Requests\UpdateInformeRequest;
use App\Interfaces\InformeInterface;
use App\Models\Informe;
use App\Models\Solicitud;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class InformeController extends Controller
{
    public function __construct(
        private InformeInterface $repository,
    ) {}

    public function index() {}

    /**
     * Show the form for creating a new resource.
     */
    public function create(Solicitud $solicitud)
    {
        return Inertia::render('Solicituds/Informe/Form', [
            'solicitud' => $solicitud,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInformeRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->repository->create($request->validated());
            DB::commit();

            return back()->with('status', 'Informe create successfully');
        } catch (\Exception $e) {
            dd($e->getMessage());

            return back()->withErrors(['errors' => 'Action no Disabled']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Informe $informe)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Informe $informe)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInformeRequest $request, Informe $informe)
    {
        try {
            DB::beginTransaction();
            $this->repository->update($informe->id, $request->validated());
            DB::commit();

            return back()->with('status', 'Informe updated successfully');
        } catch (\Exception $e) {
            dd($e->getMessage());

            return back()->withErrors(['errors' => 'Action no Disabled']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Informe $informe)
    {
        try {
            DB::beginTransaction();
            $this->repository->delete($informe->id);
            DB::commit();

            return back()->with('status', 'Informe delete successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['errors' => 'Action no Disabled']);
        }
    }
}
