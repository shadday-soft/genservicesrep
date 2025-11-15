<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEquipoRequest;
use App\Http\Requests\UpdateEquipoRequest;
use App\Interfaces\EquipoInterface;
use App\Models\Client;
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

    public function retored(){
        $equiposAnteriores = DB::connection('dbantes')->table('equipos')->get();
        foreach($equiposAnteriores as $equipo){
            $sucursalAnterior = DB::connection('dbantes')->table('sucursales')->where('id', $equipo->sucursal_id)->first();
            $sucursal = DB::table('sucursals')->where('name', $sucursalAnterior->nombre_sucursal)->first();
            $nit = DB::connection('dbantes')->table('users')->where('id', $sucursalAnterior->user_id)->value('nit');
            $client = Client::where('nit', $nit)->first();
            $this->repository->create([
                'sucursal_id' => $sucursal->id,
                'nombre_equipo' => $equipo->nombre,
                'detalles' => $equipo->Detalles,
                'client_id' => $client->id,
                'tipo_equipo' => $equipo->tipo_equipo,
                'potencia' => $equipo->potencia,
                'modelo_equipo' => $equipo->modelo_equipo,
                'modelo_motor' => $equipo->modelo_motor,
                'tension_operacion' => $equipo->tension_operacion,
                'serie_equipo' => $equipo->serie_equipo,
                'serie_motor' => $equipo->serie_motor,
                'marca_generador' => $equipo->marca_generador,
                'horometro' => $equipo->horometro,
                'marca_motor' => $equipo->marca_motor,
                'tablero_tipo_aplicacion' => $equipo->tablero_tipo ?? null,
                'tablero_tension_operacion' => $equipo->tablero_tension_operacion ?? null,
                'tablero_fabricante' => $equipo->fabricante ?? null,
                'tablero_corriente_nominal' => $equipo->corriente_nominal ?? null,
                'tablero_elemento_maniobra' => $equipo->elemento_maniobra?? null,
                'tablero_controlador' => $equipo->controlador_ats ?? null,
                'filtro_aire_cantidad' => $equipo->cantidad_filtro_aire ?? null,
                'filtro_aire_referencia' => $equipo->referencia_filtro_aire ?? null,
                'filtro_aceite_cantidad' => $equipo->cantidad_filtro_aceite ?? null,
                'filtro_aceite_referencia' => $equipo->referencia_filtro ?? null,
                'filtro_combustible_cantidad' => $equipo->cantidad_filtro_combustible ?? null,
                'filtro_combustible_referencia' => $equipo->referencia_filtro_combustible ?? null,
                'filtro_separador_cantidad' => $equipo->cantidad_filtro_separador ?? null,
                'filtro_separador_referencia' => $equipo->referencia_filtro_separador ?? null,
                'filtro_agua_cantidad' => $equipo->cantidad_filtro_agua ?? null,
                'filtro_agua_referencia' => $equipo->referencia_filtro_agua ?? null,
                'filtro_aceite_2_cantidad' => $equipo->cantidad_filtro_aceite_2 ?? null,
                'filtro_aceite_2_referencia' => $equipo->referencia_filtro_aceite_2 ?? null,
                'refrigerante_cantidad' => $equipo->cantidad_cantidad_refrigerante_liquido ?? null,
                'refrigerante_referencia' => $equipo->referencia_cantidad_cantidad_refrigerante_liquido ?? null,
            ]);
        }
        return Equipo::all();
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
