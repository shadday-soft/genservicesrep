<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTableroElectricoRequest;
use App\Interfaces\TableroElectricoInterface;
use App\Models\TableroElectrico;
use Illuminate\Support\Facades\DB;

class TableroElectricoController extends Controller
{
    public function __construct(
        private TableroElectricoInterface $repository,
    ) {}

    public function store(StoreTableroElectricoRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->repository->create($request->validated());
            DB::commit();

            return back()->with('status', 'Informe de Tablero Eléctrico creado con éxito');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ocurrió un error al crear el informe: '.$e->getMessage()]);
        }
    }

    public function update(StoreTableroElectricoRequest $request, TableroElectrico $tableroElectrico)
    {
        try {
            DB::beginTransaction();
            $this->repository->update($tableroElectrico->id, $request->validated());
            DB::commit();

            return back()->with('status', 'Informe de Tablero Eléctrico actualizado con éxito');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ocurrió un error al actualizar el informe: '.$e->getMessage()]);
        }
    }
}
