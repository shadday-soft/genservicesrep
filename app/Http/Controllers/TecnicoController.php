<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTecnicoRequest;
use App\Http\Requests\UpdateTecnicoRequest;
use App\Interfaces\TecnicoInterface;
use App\Models\Tecnico;
use Illuminate\Support\Facades\DB;

class TecnicoController extends Controller
{
    public function __construct(
        private TecnicoInterface $repository,
    ) {}

    public function index()
    {
        $search = request('search');
        $perPage = request('per_page', 15);

        $tecnicos = $this->repository->getAll($perPage, $search);

        if (request()->wantsJson()) {
            return response()->json([
                'tecnicos' => $this->repository->getAllData(),
            ]);
        }

        return inertia('Tecnicos/Index', [
            'tecnicos' => $tecnicos,
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
    public function store(StoreTecnicoRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->repository->create($request->validated());
            DB::commit();

            return back()->with('status', 'Tecnico create successfully');
        } catch (\Exception $e) {
            return back()->withError('errors', 'Action no Disabled');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tecnico $tecnico)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tecnico $tecnico)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTecnicoRequest $request, Tecnico $tecnico)
    {
        try {
            DB::beginTransaction();
            $this->repository->update($tecnico->id, $request->validated());
            DB::commit();

            return back()->with('status', 'Tecnico updated successfully');
        } catch (\Exception $e) {
            return back()->withError('errors', 'Action no Disabled');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tecnico $tecnico)
    {
        try {
            DB::beginTransaction();
            $this->repository->delete($tecnico->id);
            DB::commit();

            return back()->with('status', 'Tecnico delete successfully');
        } catch (\Exception $e) {
            return back()->withError('errors', 'Action no Disabled');
        }
    }
}
