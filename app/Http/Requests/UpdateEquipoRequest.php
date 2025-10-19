<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        // detect current equipo id (for update requests) to ignore uniques
        $equipo = $this->route('equipo');
        $equipoId = null;
        if ($equipo) {
            if (is_object($equipo) && isset($equipo->id)) {
                $equipoId = $equipo->id;
            } elseif (is_string($equipo) || is_int($equipo)) {
                $equipoId = $equipo;
            }
        }

        // prepare unique rules for series (considering update ignore)
        $serieEquipoRules = ['nullable', 'string', 'max:100'];
        $serieMotorRules = ['nullable', 'string', 'max:100'];
        if ($equipoId) {
            $serieEquipoRules[] = Rule::unique('equipos', 'serie_equipo')->ignore($equipoId, 'id');
            $serieMotorRules[] = Rule::unique('equipos', 'serie_motor')->ignore($equipoId, 'id');
        } else {
            $serieEquipoRules[] = Rule::unique('equipos', 'serie_equipo');
            $serieMotorRules[] = Rule::unique('equipos', 'serie_motor');
        }

        return [
            // --- DATOS GENERALES ---
            'nombre_equipo' => ['required', 'string', 'max:100'],
            'client_id' => ['nullable', 'uuid', 'exists:empresas,id'],
            'sucursal_id' => ['nullable', 'uuid', 'exists:sucursales,id'],
            'detalles' => ['nullable', 'string'],
            'tipo_equipo' => ['required', 'string', 'max:50'],

            // --- DETALLES DE PLANTA ELÉCTRICA ---
            // Estos campos son obligatorios sólo cuando tipo_equipo == 'Planta Eléctrica'
            'potencia' => ['required_if:tipo_equipo,Planta Eléctrica', 'string', 'max:50'],
            'modelo_equipo' => ['required_if:tipo_equipo,Planta Eléctrica', 'string', 'max:100'],
            'modelo_motor' => ['required_if:tipo_equipo,Planta Eléctrica', 'string', 'max:100'],
            'tension_operacion' => ['required_if:tipo_equipo,Planta Eléctrica', 'string', 'max:50'],
            'serie_equipo' => $serieEquipoRules,
            'serie_motor' => $serieMotorRules,
            'marca_generador' => ['required_if:tipo_equipo,Planta Eléctrica', 'string', 'max:100'],
            'horometro' => ['required_if:tipo_equipo,Planta Eléctrica', 'integer', 'min:0'],
            'marca_motor' => ['required_if:tipo_equipo,Planta Eléctrica', 'string', 'max:100'],

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
            'filtro_aire_referencia' => ['required_if:tipo_equipo,Planta Eléctrica', 'string', 'max:100'],

            'filtro_aceite_cantidad' => ['required_if:tipo_equipo,Planta Eléctrica', 'integer', 'min:0'],
            'filtro_aceite_referencia' => ['required_if:tipo_equipo,Planta Eléctrica', 'string', 'max:100'],

            'filtro_combustible_cantidad' => ['required_if:tipo_equipo,Planta Eléctrica', 'integer', 'min:0'],
            'filtro_combustible_referencia' => ['required_if:tipo_equipo,Planta Eléctrica', 'string', 'max:100'],

            'filtro_separador_cantidad' => ['required_if:tipo_equipo,Planta Eléctrica', 'integer', 'min:0'],
            'filtro_separador_referencia' => ['required_if:tipo_equipo,Planta Eléctrica', 'string', 'max:100'],

            'filtro_agua_cantidad' => ['required_if:tipo_equipo,Planta Eléctrica', 'integer', 'min:0'],
            'filtro_agua_referencia' => ['required_if:tipo_equipo,Planta Eléctrica', 'string', 'max:100'],

            'filtro_aceite_2_cantidad' => ['required_if:tipo_equipo,Planta Eléctrica', 'integer', 'min:0'],
            'filtro_aceite_2_referencia' => ['required_if:tipo_equipo,Planta Eléctrica', 'string', 'max:100'],

            'refrigerante_cantidad' => ['required_if:tipo_equipo,Planta Eléctrica', 'integer', 'min:0'],
            'refrigerante_referencia' => ['required_if:tipo_equipo,Planta Eléctrica', 'string', 'max:100'],
        ];
    }
}
