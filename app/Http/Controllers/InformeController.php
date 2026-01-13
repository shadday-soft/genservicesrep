<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInformeRequest;
use App\Http\Requests\UpdateInformeRequest;
use App\Interfaces\InformeInterface;
use App\Models\Informe;
use App\Models\Solicitud;
use App\Models\TableroElectrico;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
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
        // Cargar las relaciones necesarias
        $solicitud->load(['equipo', 'user', 'client', 'sucursal']);

        // Obtener el técnico asignado a la solicitud
        $tecnico = null;
        if ($solicitud->user && $solicitud->user->role === 'Tecnico') {
            $tecnico = $solicitud->user->tecnico;
        }

        // Determinar qué tipo de informe mostrar según el tipo de equipo
        $tipoEquipo = $solicitud->equipo->tipo_equipo ?? '';

        if ($tipoEquipo === 'Planta Eléctrica') {
            // Formulario para Planta Eléctrica
            $informe = $this->repository->findBy('solicitud_id', $solicitud->id);

            return Inertia::render('Solicituds/Informe/Form', [
                'solicitud' => $solicitud,
                'informe' => $informe,
                'equipo' => $solicitud->equipo,
                'tecnico' => $tecnico,
            ]);
        } else {
            // Formulario para Tablero Eléctrico u otros equipos
            $tablero = \App\Models\TableroElectrico::where('solicitud_id', $solicitud->id)->first();

            return Inertia::render('Solicituds/Informe/FormTablero', [
                'solicitud' => $solicitud,
                'tablero' => $tablero,
                'equipo' => $solicitud->equipo,
                'tecnico' => $tecnico,
            ]);
        }
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
        // dd($solicitud);
        // Cargar las relaciones necesarias de la solicitud
        $solicitud->load(['client', 'sucursal', 'equipo', 'user']);

        $tipoEquipo = $solicitud->equipo->tipo_equipo ?? '';
        // dd($tipoEquipo);
        if ($tipoEquipo === 'Planta Eléctrica') {
            // Generar PDF para Planta Eléctrica
            $registro = Informe::where('solicitud_id', $solicitud->id)->first();

            if (! $registro) {
                return back()->withErrors(['error' => 'No se encontró un informe para esta solicitud.']);
            }

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
            $pdf->setPaper('legal', 'portrait');

            // Descargar el PDF
            $filename = 'Informe_Planta_Electrica_'.$solicitud->numero_orden.'.pdf';

            return $pdf->stream($filename);
        } else {
            // Generar PDF para Tablero Eléctrico
            $registro = \App\Models\TableroElectrico::where('solicitud_id', $solicitud->id)->first();
            // Preparar los datos que necesita la vista
            $registro->numero_orden = $solicitud->numero_orden;
            $registro->numero_solicitud = $solicitud->id;
            $registro->quiensolicita_id = $solicitud->quien_solicita;
            $registro->telefono = $solicitud->client->phone_number;
            $registro->mail = $solicitud->client->email;
            $registro->ubicacion = $solicitud->sucursal->address;
            $registro->user = $solicitud->user;
            $registro->sucursal = $solicitud->sucursal;
            $registro->equipo = $solicitud->equipo;

            // Generar el PDF
            $pdf = Pdf::loadView('pdf.tablero_electrico', compact('registro', 'solicitud'));
            $pdf->setPaper('legal', 'portrait');

            // Descargar el PDF
            $filename = 'Informe_Tablero_Electrico_'.$solicitud->numero_orden.'.pdf';

            return $pdf->stream($filename);
        }
    }

   

    public function downloadAllInformes()
    {
        $outputDir = public_path('pdf');
        File::ensureDirectoryExists($outputDir);

        $solicitudes = Solicitud::with(['client', 'sucursal', 'equipo', 'user'])->take(5)->get();
        $saved = 0;
        $errors = [];

        foreach ($solicitudes as $solicitud) {
            $tipoEquipo = $solicitud->equipo->tipo_equipo ?? '';

            try {
                if ($tipoEquipo === 'Planta Eléctrica') {
                    $registro = Informe::where('solicitud_id', $solicitud->id)->first();

                    if (! $registro) {
                        $errors[] = "Solicitud {$solicitud->id}: no se encontró informe de Planta Eléctrica";
                        continue;
                    }

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

                    $pdf = Pdf::loadView('pdf.planta_electrica', compact('registro', 'solicitud'));
                    $pdf->setPaper('legal', 'portrait');

                    $filename = 'Informe_Planta_Electrica_'.$solicitud->numero_orden.'.pdf';
                    File::put($outputDir.DIRECTORY_SEPARATOR.$filename, $pdf->output());
                    $saved++;
                } elseif ($tipoEquipo === 'Tablero Eléctrico') {
                    $registro = TableroElectrico::where('solicitud_id', $solicitud->id)->first();

                    if (! $registro) {
                        $errors[] = "Solicitud {$solicitud->id}: no se encontró informe de Tablero Eléctrico";
                        continue;
                    }

                    // Preparar los datos que necesita la vista
                    $registro->numero_orden = $solicitud->numero_orden;
                    $registro->numero_solicitud = $solicitud->id;
                    $registro->quiensolicita_id = $solicitud->quien_solicita;
                    $registro->telefono = $solicitud->client->phone_number ?? $solicitud->telefono ?? '';
                    $registro->mail = $solicitud->client->email ?? $solicitud->mail ?? '';
                    $registro->ubicacion = $solicitud->sucursal->address ?? $solicitud->ubicacion ?? '';
                    $registro->user = $solicitud->user;
                    $registro->sucursal = $solicitud->sucursal;
                    $registro->equipo = $solicitud->equipo;

                    $pdf = Pdf::loadView('pdf.tablero_electrico', compact('registro', 'solicitud'));
                    $pdf->setPaper('legal', 'portrait');

                    $filename = 'Informe_Tablero_Electrico_'.$solicitud->numero_orden.'.pdf';
                    File::put($outputDir.DIRECTORY_SEPARATOR.$filename, $pdf->output());
                    $saved++;
                }
            } catch (\Throwable $e) {
                $errors[] = "Solicitud {$solicitud->id}: {$e->getMessage()}";
            }
        }

        if (count($errors) > 0) {
            return back()->withErrors([
                'error' => "Se guardaron {$saved} PDF(s) en public/pdf. Errores: ".implode(' | ', $errors),
            ]);
        }

        return back()->with('status', "Se guardaron {$saved} PDF(s) en public/pdf");
    }
}
