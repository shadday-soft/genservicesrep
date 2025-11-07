<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// Store request used for creation - no ignore logic needed for uniques

class UpdateEquipoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            // --- DATOS GENERALES ---
            'nombre_equipo' => ['required', 'string', 'max:100'],
            'client_id' => ['nullable', 'uuid', 'exists:clients,id'],
            'sucursal_id' => ['nullable', 'uuid', 'exists:sucursals,id'],
            'detalles' => ['nullable', 'string'],
            'tipo_equipo' => ['required', 'string', 'max:50'],
            'proximas_fechas_mantenimiento' => ['nullable', 'array'],
            'proximas_fechas_mantenimiento.*' => ['date'],
            'fecha_primer_mantenimiento' => ['nullable', 'date'],

            // --- DETALLES DE PLANTA ELÉCTRICA ---
            // Estos campos son obligatorios sólo cuando tipo_equipo == 'Planta Eléctrica'
            'potencia' => ['required_if:tipo_equipo,Planta Eléctrica',  'max:50'],
            'modelo_equipo' => ['required_if:tipo_equipo,Planta Eléctrica',  'max:100'],
            'modelo_motor' => ['required_if:tipo_equipo,Planta Eléctrica',  'max:100'],
            'tension_operacion' => ['required_if:tipo_equipo,Planta Eléctrica',  'max:50'],
            'serie_equipo' => ['nullable',  'max:100', 'unique:equipos,serie_equipo'],
            'serie_motor' => ['nullable',  'max:100', 'unique:equipos,serie_motor'],
            'marca_generador' => ['required_if:tipo_equipo,Planta Eléctrica',  'max:100'],
            'horometro' => ['required_if:tipo_equipo,Planta Eléctrica', 'min:0', 'numeric', 'nullable'],
            'marca_motor' => ['required_if:tipo_equipo,Planta Eléctrica',  'max:100'],

            // --- DETALLES DE TABLERO ELÉCTRICO ---
            // Estos campos son obligatorios cuando tipo_equipo != 'Planta Eléctrica'
            'tablero_tipo' => ['required_unless:tipo_equipo,Planta Eléctrica', 'nullable', 'string', 'max:100'],
            'tablero_tension_operacion' => ['required_unless:tipo_equipo,Planta Eléctrica', 'nullable', 'string', 'max:50'],
            'tablero_tipo_aplicacion' => ['required_unless:tipo_equipo,Planta Eléctrica', 'nullable', 'string', 'max:100'],
            'tablero_fabricante' => ['required_unless:tipo_equipo,Planta Eléctrica', 'nullable', 'string', 'max:100'],
            'tablero_corriente_nominal' => ['required_unless:tipo_equipo,Planta Eléctrica', 'nullable', 'string', 'max:50'],
            'tablero_elemento_maniobra' => ['required_unless:tipo_equipo,Planta Eléctrica', 'nullable', 'string', 'max:100'],
            'tablero_controlador' => ['required_unless:tipo_equipo,Planta Eléctrica', 'nullable', 'string', 'max:100'],

            // --- INSUMOS (Cantidades y Referencias) ---
            // Los insumos no son obligatorios cuando tipo_equipo != 'Planta Eléctrica'
            'filtro_aire_cantidad' => ['required_if:tipo_equipo,Planta Eléctrica', 'integer', 'min:0'],
            'filtro_aire_referencia' => ['required_if:tipo_equipo,Planta Eléctrica',  'max:100'],

            'filtro_aceite_cantidad' => ['required_if:tipo_equipo,Planta Eléctrica', 'integer', 'min:0'],
            'filtro_aceite_referencia' => ['required_if:tipo_equipo,Planta Eléctrica',  'max:100'],

            'filtro_combustible_cantidad' => ['required_if:tipo_equipo,Planta Eléctrica', 'integer', 'min:0'],
            'filtro_combustible_referencia' => ['required_if:tipo_equipo,Planta Eléctrica', 'max:100'],

            'filtro_separador_cantidad' => ['required_if:tipo_equipo,Planta Eléctrica', 'integer', 'min:0'],
            'filtro_separador_referencia' => ['required_if:tipo_equipo,Planta Eléctrica',  'max:100'],

            'filtro_agua_cantidad' => ['required_if:tipo_equipo,Planta Eléctrica', 'integer', 'min:0'],
            'filtro_agua_referencia' => ['required_if:tipo_equipo,Planta Eléctrica',  'max:100'],

            'filtro_aceite_2_cantidad' => ['required_if:tipo_equipo,Planta Eléctrica', 'integer', 'min:0'],
            'filtro_aceite_2_referencia' => ['required_if:tipo_equipo,Planta Eléctrica',  'max:100'],

            'refrigerante_cantidad' => ['required_if:tipo_equipo,Planta Eléctrica', 'integer', 'min:0'],
            'refrigerante_referencia' => ['required_if:tipo_equipo,Planta Eléctrica',  'max:100'],

            // --- PROGRAMACIÓN DE MANTENIMIENTOS ---
            'periodicidad' => ['nullable', 'string', 'max:200'],
            'fecha_primer_mantenimiento' => ['nullable', 'date'],
            'proximas_fechas_mantenimiento' => ['nullable', 'array'],
        ];
    }
}
