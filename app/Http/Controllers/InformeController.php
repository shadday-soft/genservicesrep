<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInformeRequest;
use App\Http\Requests\UpdateInformeRequest;
use App\Interfaces\InformeInterface;
use App\Models\Informe;
use App\Models\Solicitud;
use Barryvdh\DomPDF\Facade\Pdf;
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
        $informe = $this->repository->findBy('solicitud_id', $solicitud->id);

        // Cargar las relaciones necesarias
        $solicitud->load(['equipo', 'user']);

        // Obtener el técnico asignado a la solicitud
        $tecnico = null;
        if ($solicitud->user && $solicitud->user->role === 'Tecnico') {
            $tecnico = $solicitud->user->tecnico;
        }

        return Inertia::render('Solicituds/Informe/Form', [
            'solicitud' => $solicitud,
            'informe' => $informe,
            'equipo' => $solicitud->equipo,
            'tecnico' => $tecnico,
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

    /**
     * Generate PDF report for a solicitud
     */
    public function generatePDF(Solicitud $solicitud)
    {
        // Cargar el informe de planta eléctrica relacionado con la solicitud
        $registro = Informe::where('solicitud_id', $solicitud->id)->first();

        if (! $registro) {
            return back()->withErrors(['error' => 'No se encontró un informe para esta solicitud.']);
        }

        // Cargar las relaciones necesarias de la solicitud
        $solicitud->load(['client', 'sucursal', 'equipo', 'user']);
        $solicitud->load(['client', 'sucursal', 'equipo', 'user']);

        // Preparar los datos que necesita la vista
        $registro->numero_orden = $solicitud->numero_orden;
        $registro->fecha_solicitud = $solicitud->fecha_programada;
        $registro->quien_solicita = $solicitud->quien_solicita;
        $registro->telefono = $solicitud->telefono;
        $registro->mail = $solicitud->mail;
        $registro->ubicacion = $solicitud->ubicacion;
        $registro->user = $solicitud->user;
        $registro->sucursal = $solicitud->sucursal;

        // Si el equipo tiene los datos, agregarlos
        if ($solicitud->equipo) {
            $registro->modelo_equipo = $registro->modelo_equipo ?? $solicitud->equipo->modelo_equipo ?? '';
            $registro->serie_equipo = $registro->serie_equipo ?? $solicitud->equipo->serie_equipo ?? '';
            $registro->marca_generador = $registro->marca_generador ?? $solicitud->equipo->marca_generador ?? '';
            $registro->horometro = $registro->horometro ?? $solicitud->equipo->horometro ?? '';
            $registro->modelo_motor = $registro->modelo_motor ?? $solicitud->equipo->modelo_motor ?? '';
            $registro->serie_motor = $registro->serie_motor ?? $solicitud->equipo->serie_motor ?? '';
            $registro->marca_motor = $registro->marca_motor ?? $solicitud->equipo->marca_motor ?? '';
            $registro->tension_operacion = $registro->tension_operacion ?? $solicitud->equipo->tension_operacion ?? '';
        }

        // Generar el PDF
        $pdf = Pdf::loadView('pdf.planta_electrica', compact('registro', 'solicitud'));
        $pdf->setPaper('letter', 'portrait');

        // Descargar el PDF
        $filename = 'Informe_Planta_Electrica_'.$solicitud->numero_orden.'.pdf';

        return $pdf->stream($filename);
    }
}
