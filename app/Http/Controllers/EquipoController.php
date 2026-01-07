<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEquipoRequest;
use App\Http\Requests\UpdateEquipoRequest;
use App\Interfaces\EquipoInterface;
use App\Models\Client;
use App\Models\Equipo;
use App\Models\Sucursal;
use App\Models\User;
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

    public function retored()
    {
        $equipos = DB::connection('dbantes')->table('equipos')->get();
        foreach ($equipos as $e) {
            $client = $this->crearCliente($e->user_id);
            $sucursalName = DB::connection('dbantes')->table('sucursales')->where('id', $e->sucursal_id)->first();
            $sucursal = Sucursal::where('name', $sucursalName->nombre_sucursal)->first();
            $equipo = Equipo::firstOrNew([
                'nombre_equipo' => $e->nombre,
            ]);
            if(isset($equipo->id)) continue;
            $equipo->tipo_equipo = $e->tipo_equipo;
            
            $equipo->client_id = $client->id;
            $equipo->sucursal_id = $sucursal->id;
            $equipo->detalles = $e->Detalles;
            $equipo->potencia = $e->potencia;
            $equipo->modelo_equipo = $e->modelo_equipo;
            $equipo->modelo_motor = $e->modelo_motor;
            $equipo->tension_operacion = $e->tension_operacion;
            $equipo->serie_equipo = $e->serie_equipo;
            $equipo->serie_motor = $e->serie_motor;
            $equipo->marca_generador = $e->marca_generador;
            $equipo->horometro = $e->horometro;
            $equipo->marca_motor = $e->marca_motor;
            $equipo->tablero_tipo = $e->tablero_tipo ?? null;
            $equipo->tablero_tension_operacion = $e->tablero_tension_operacion ?? null;
            $equipo->tablero_tipo_aplicacion = $e->tablero_tipo_aplicacion ?? null;
            $equipo->tablero_fabricante = $e->fabricante ?? null;
            $equipo->tablero_corriente_nominal = $e->corriente_nominal ?? null;
            $equipo->tablero_elemento_maniobra = $e->elemento_maniobra ?? null;
            $equipo->tablero_controlador = $e->controlador_ats ?? null;
            $equipo->filtro_aire_cantidad = $e->cantidad_filtro_aire;
            $equipo->filtro_aire_referencia = $e->referencia_filtro_aire ?? null;
            $equipo->filtro_aceite_cantidad = $e->cantidad_filtro_aceite ?? null;
            $equipo->filtro_aceite_referencia = $e->referencia_filtro ?? null;
            $equipo->filtro_combustible_cantidad = $e->cantidad_filtro_combustible ?? null;
            $equipo->filtro_combustible_referencia = $e->referencia_filtro_combustible ?? null;
            $equipo->filtro_separador_cantidad = $e->cantidad_filtro_separador ?? null;
            $equipo->filtro_separador_referencia = $e->referencia_filtro_separador ?? null;
            $equipo->filtro_agua_cantidad = $e->cantidad_filtro_agua ?? null;
            $equipo->filtro_agua_referencia = $e->referencia_filtro_agua ?? null;
            $equipo->filtro_aceite_2_cantidad = $e->cantidad_filtro_aceite_2 ?? null;
            $equipo->filtro_aceite_2_referencia = $e->referencia_filtro_aceite_2 ?? null;
            $equipo->refrigerante_cantidad = $e->cantidad_refrigerante ?? null;
            $equipo->refrigerante_referencia = $e->referencia_refrigerante ?? null;
           
            $equipo->save();
        }
        return Equipo::all();
    }

    private function crearCliente($id)
    {
        $cliente = DB::connection('dbantes')->table('users')->where('id', $id)->first();
        if (Client::where('nit', $cliente->nit)->exists()) {
            return Client::where('nit', $cliente->nit)->first();
        }
        $userData = [
            'name' => $cliente->name,
            'email' => $cliente->email,
            'password' => bcrypt($cliente->nit),
        ];
        $user = User::firstOrNew([
            'email' => $cliente->email,
        ]);
        $user->name = $cliente->name;
        $user->password = bcrypt($cliente->nit);
        $user->save();

        $clientData = [
            'user_id' => $user->id,
            'enterprise_name' => $cliente->name,
            'email' => $cliente->email,
            'nit' => $cliente->nit,
            'contact_name' => $cliente->nombre_contacto,
            'phone_number' => $cliente->contacto,
        ];
        $client = Client::create($clientData);
        return $client;
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

    public function show(Equipo $equipo)
    {
        //
    }

    public function edit(Equipo $equipo)
    {
        //
    }

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
