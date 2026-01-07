<?php

namespace App\Http\Controllers;

use App\Exports\SolicitudesExport;
use App\Http\Requests\StoreSolicitudRequest;
use App\Http\Requests\UpdateSolicitudRequest;
use App\Interfaces\ClientInterface;
use App\Interfaces\EquipoInterface;
use App\Interfaces\SolicitudInterface;
use App\Interfaces\SucursalInterface;
use App\Interfaces\TecnicoInterface;
use App\Models\Client;
use App\Models\Equipo;
use App\Models\Informe;
use App\Models\Solicitud;
use App\Models\Sucursal;
use App\Models\TableroElectrico;
use App\Models\Tecnico;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SolicitudController extends Controller
{
    public function __construct(
        private SolicitudInterface $repository,
        private TecnicoInterface $tecnicoRepository,
        private ClientInterface $clientRepository,
        private SucursalInterface $sucursalRepository,
        private EquipoInterface $equipoRepository
    ) {}

    public function index()
    {
        $search = request('search');
        $perPage = request('per_page', 15);
        $tipoSolicitudes = request('tipo');
        $estado = request('estado');
        $mes = request('mes');
        $anio = request('anio');

        $solicituds = $this->repository->getAllSolicitudes($perPage, $search, $tipoSolicitudes, $estado, $mes, $anio);

        if (request()->wantsJson()) {
            return response()->json([
                'solicituds' => $this->repository->getAllData(),
            ], 200);
        }

        return inertia('Solicituds/Index', [
            'solicituds' => $solicituds,
            'filters' => [
                'search' => $search,
                'per_page' => $perPage,
                'tipo' => $tipoSolicitudes,
                'estado' => $estado,
                'mes' => $mes,
                'anio' => $anio,
            ],
        ]);
    }

    public function reestored()
    {
        $solicitudsAnteriores = DB::connection('dbantes')->table('solicitudes')->get();
        foreach ($solicitudsAnteriores as $solicitud) {
            $cliente = $this->crearCliente($solicitud->user_id);
            $tecnico = $this->crearTenico($solicitud->assigment_id);
            $sucursal = $this->crearSucursal($solicitud->sucursal_id, $cliente->id);
            $equipo = $this->crearEquipo($solicitud->equipos_id, $sucursal->id, $cliente->id);

            // Lógica para restaurar cada solicitud
            $s = Solicitud::create([
                // 'numero_orden' => $solicitud->numero_orden,
                'fecha_programada' => $solicitud->start_date
                    ? \Carbon\Carbon::parse($solicitud->start_date)->setTimezone('UTC')->format('Y-m-d\TH:i:s.v\Z')
                    : null,
                'quien_solicita' => $solicitud->quien_solicita,
                'telefono' => $solicitud->contacto,
                'mail' => $solicitud->mail,
                'ubicacion' => $solicitud->ubicacion,
                'prioridad' => $solicitud->prioridad,
                'detalles' => $solicitud->detalles,
                'estado' => $solicitud->status,
                'actividad' => $solicitud->actividad,
                'client_id' => $cliente->id,
                'sucursal_id' => $sucursal->id,
                'equipo_id' => $equipo->id,
                'user_id' => $tecnico->user_id ?? null,
                'last_num_order' => $solicitud->numero_orden,
            ]);
            $sucursal->address = $solicitud->ubicacion ?? null;
            $sucursal->save();

            $s->created_at = $solicitud->created_at ?? now();
            $s->save();

        }
    }

    public function crearTenico($id)
    {
        $tecnicoAnterior = DB::connection('dbantes')->table('users')->where('id', $id)->first();
        if (! $tecnicoAnterior) {
            return null;
        }
        $tecnicoData = [
            'nombre_completo' => $tecnicoAnterior->name,
            'correo' => $tecnicoAnterior->email,
            'identificacion' => (string) random_int(10 ** 7, 10 ** 12 - 1),
            'fecha_inicio_contrato' => '2025-11-15T02:23:56.661Z',
            'tipo_contrato' => 'Indefinido',
            'activo' => true,
        ];
        if (Tecnico::where('correo', $tecnicoAnterior->email)->exists()) {
            return Tecnico::where('correo', $tecnicoAnterior->email)->first();
        }

        return $this->tecnicoRepository->create(
            $tecnicoData
        );
    }

    public function crearSucursal($id, $client)
    {
        $sucursal = DB::connection('dbantes')->table('sucursales')->where('id', $id)->first();
        if (Sucursal::where('email', $sucursal->mail)->exists()) {
            return Sucursal::where('email', $sucursal->mail)->first();
        }

        return $this->sucursalRepository->create([
            'client_id' => $client,
            'name' => $sucursal->nombre_sucursal,
            'address' => null,
            'phone_number' => $sucursal->contacto,
            'contact_name' => $sucursal->nombre_contacto,
            'email' => $sucursal->mail,
        ]);
    }

    public function crearEquipo($id, $sucursal, $client)
    {
        $equipo = DB::connection('dbantes')->table('equipos')->where('id', $id)->first();
        if (Equipo::where('nombre_equipo', $equipo->nombre)->where('sucursal_id', $sucursal)->exists()) {
            return Equipo::where('nombre_equipo', $equipo->nombre)->where('sucursal_id', $sucursal)->first();
        }

        return $this->equipoRepository->create([
            'sucursal_id' => $sucursal,
            'nombre_equipo' => $equipo->nombre,
            'detalles' => $equipo->Detalles,
            'client_id' => $client,
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
            'tablero_elemento_maniobra' => $equipo->elemento_maniobra ?? null,
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

    public function crearCliente($id)
    {
        $cliente = DB::connection('dbantes')->table('users')->where('id', $id)->first();

        if (Client::where('nit', $cliente->nit)->exists()) {
            return Client::where('nit', $cliente->nit)->first();
        }

        return $this->clientRepository->create([
            'enterprise_name' => $cliente->name,
            'email' => $cliente->email,
            'nit' => $cliente->nit,
            'contact_name' => $cliente->nombre_contacto,
            'phone_number' => $cliente->contacto,
        ]);
    }

    public function reestoredPlantaElectrica()
    {
        $informesAnteriores = DB::connection('dbantes')->table('plantas_electricas')->get();
        foreach ($informesAnteriores as $informe) {
            $solicitud = Solicitud::where('last_num_order', $informe->numero_solicitud)->first();
            if (! $solicitud) {
                // Skip if no matching solicitud found
                continue;
            }

            $informeNew = new Informe;

            // Copy properties from the stdClass record to the Eloquent model,
            // skipping the original primary key and numero_orden to avoid collisions.
            foreach (get_object_vars($informe) as $key => $value) {
                if (in_array($key, ['id', 'numero_orden'], true)) {
                    continue;
                }
                if (str_contains($key, 'foto')) {
                    $informeNew->{$key} = 'https://reporting.genservices.com.co/storage'.$value;

                    continue;
                }
                $informeNew->{$key} = $value;
            }
            unset($informeNew->numero_solicitud);
            unset($informeNew->user_id);
            unset($informeNew->client_id);
            unset($informeNew->equipo_id);
            unset($informeNew->ubicacion);
            unset($informeNew->mail);
            unset($informeNew->quien_solicita);
            unset($informeNew->actividad);
            unset($informeNew->modelo_equipo);
            unset($informeNew->tension_operacion);
            unset($informeNew->serie_equipo);
            unset($informeNew->serie_motor);
            unset($informeNew->horometro);
            unset($informeNew->marca_motor);
            unset($informeNew->limpieza_generador);
            unset($informeNew->cantidad_cantidad_refrigerante_liquido);
            unset($informeNew->referencia_cantidad_cantidad_refrigerante_liquido);
            unset($informeNew->marca_generador);
            unset($informeNew->modelo_motor);
            unset($informeNew->telefono);
            unset($informeNew->quien_recibe);
            unset($informeNew->sede_id);
            unset($informeNew->assigment_id);
            // Ensure the relation to the restored solicitud
            $informeNew->solicitud_id = $solicitud->id;
            $informeNew->bajo_voltaje_ac = $informe->bajo_voltaje_de_ac;
            unset($informeNew->bajo_voltaje_de_ac);
            $informeNew->save();
            $solicitud->informe_generado = true;
            $solicitud->save();
        }

    }


    public function reestoredTableroElectrico(){
        $informesAnteriores = DB::connection('dbantes')->table('tableros_electricos')->get();
        foreach ($informesAnteriores as $informe) {
            $solicitud = Solicitud::where('last_num_order', $informe->numero_solicitud)->first();
            if (! $solicitud) {
                // Skip if no matching solicitud found
                continue;
            }
            $tableroNew = new TableroElectrico();
            // Copy properties from the stdClass record to the Eloquent model,
            foreach (get_object_vars($informe) as $key => $value) {
                if (in_array($key, ['id', 'numero_orden'], true)) {
                    continue;
                }
                if (str_contains($key, 'foto')) {
                    $tableroNew->{$key} = 'https://reporting.genservices.com.co/storage'.$value;

                    continue;
                }
                $tableroNew->{$key} = $value;
            
            }
            unset($tableroNew->numero_orden);
            unset($tableroNew->numero_solicitud);
            unset($tableroNew->assigment_id);
            unset($tableroNew->quiensolicita_id);
            unset($tableroNew->quien_recibe);
            unset($tableroNew->telefono);
            unset($tableroNew->actividad);
            unset($tableroNew->mail);
            unset($tableroNew->ubicacion);
            unset($tableroNew->sede_id);
            unset($tableroNew->equipo_id);
            unset($tableroNew->user_id);
            unset($tableroNew->tipo_tablero);
            $tableroNew->solicitud_id = $solicitud->id;
            $tableroNew->save();
            $solicitud->informe_generado = true;
            $solicitud->save();
        }
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
        try {
            DB::beginTransaction();
            $this->repository->create($request->validated());
            DB::commit();

            return back()->with('status', 'Solicitud create successfully');
        } catch (\Exception $e) {
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

        DB::beginTransaction();
        $this->repository->update($solicitud->id, $request->validated());
        DB::commit();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Solicitud $solicitud)
    {
        try {
            DB::beginTransaction();
            $this->repository->delete($solicitud->id);
            DB::commit();

            return back()->with('status', 'Solicitud delete successfully');
        } catch (\Exception $e) {
            return back()->withError('errors', 'Action no Disabled');
        }
    }

    /**
     * Cancelar una solicitud (cambiar estado a Finalizada)
     */
    public function cancelar(Solicitud $solicitud)
    {
        try {
            $razonCancelacion = request('razon_cancelacion');

            if (! $razonCancelacion) {
                return response()->json([
                    'error' => 'La razón de cancelación es requerida',
                ], 422);
            }

            DB::beginTransaction();

            $solicitud->estado = 'Finalizada';
            $solicitud->razon_cancelacion = $razonCancelacion;
            $solicitud->save();

            DB::commit();

            return back()->with('status', 'Solicitud cancelada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['errors' => 'Action no Disabled']);
        }
    }

    /**
     * Muestra el cronograma tipo Gantt de las solicitudes
     */
    public function cronograma()
    {
        $solicituds = $this->repository->getSolicitudesParaCronograma();

        return inertia('Solicituds/Cronogram', [
            'solicituds' => $solicituds,
        ]);
    }

    /**
     * Exportar solicitudes a Excel
     */
    public function exportExcel()
    {
        $search = request('search');
        $tipo = request('tipo');
        $estado = request('estado');
        $mes = request('mes');
        $anio = request('anio');

        $filename = 'solicitudes_'.now()->format('Y-m-d_His').'.xlsx';

        return Excel::download(new SolicitudesExport($search, $tipo, $estado, $mes, $anio), $filename);
    }
}
