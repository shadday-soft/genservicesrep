<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSucursalRequest;
use App\Http\Requests\UpdateSucursalRequest;
use App\Interfaces\SucursalInterface;
use App\Models\Sucursal;
use Illuminate\Support\Facades\DB;

class SucursalController extends Controller
{
    public function __construct(
        private SucursalInterface $repository,
    ) {}

    public function index()
    {
        $search = request('search');
        $perPage = request('per_page', 15);

        $sucursals = $this->repository->getAll($perPage, $search);

        if (request()->wantsJson()) {
            return response()->json([
                'sucursals' => $this->repository->getAllData(),
            ]);
        }

        return inertia('Sucursals/Index', [
            'sucursals' => $sucursals,
            'filters' => [
                'search' => $search,
                'per_page' => $perPage,
            ],
        ]);
    }

    public function retored()
    {

        return Sucursal::all();
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
    public function store(StoreSucursalRequest $request)
    {
        try {

            DB::beginTransaction();
            $this->repository->create($request->validated());
            DB::commit();

            return back()->with('status', 'Sucursal create successfully');
        } catch (\Exception $e) {
            return back()->withError('errors', 'Action no Disabled');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sucursal $sucursal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sucursal $sucursal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSucursalRequest $request, Sucursal $sucursal)
    {
        try {
            DB::beginTransaction();
            $this->repository->update($sucursal->id, $request->validated());
            DB::commit();

            return back()->with('status', 'Sucursal updated successfully');
        } catch (\Exception $e) {
            return back()->withError('errors', 'Action no Disabled');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sucursal $sucursal)
    {
        try {
            DB::beginTransaction();
            $this->repository->delete($sucursal->id);
            DB::commit();

            return back()->with('status', 'Sucursal delete successfully');
        } catch (\Exception $e) {
            return back()->withError('errors', 'Action no Disabled');
        }
    }
}
