<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInformeRequest extends FormRequest
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
            // Información básica
            'solicitud_id' => ['required', 'exists:solicituds,id'],
            'tipo_servicio' => ['required', 'string', 'in:Mantenimiento,Servicio,Inspeccion,Soporte,Emergencia'],
            'observaciones_iniciales' => ['nullable', 'string'],

            // Estado inicial - valores cortos (B/R/M/N/A)
            'nivel_aceite' => ['nullable', 'string', 'max:10'],
            'nivel_refrigerante' => ['nullable', 'string', 'max:10'],
            'nivel_combustible' => ['nullable', 'string', 'max:50'],
            'capacidad_tanque' => ['nullable', 'string', 'max:50'],
            'fugas' => ['nullable', 'string'],
            'mangueras' => ['nullable', 'string', 'max:10'],
            'sellos' => ['nullable', 'string', 'max:10'],
            'tuberias' => ['nullable', 'string', 'max:10'],
            'radiador' => ['nullable', 'string', 'max:10'],
            'guardas' => ['nullable', 'string', 'max:10'],
            'correas_ventilador' => ['nullable', 'string', 'max:10'],
            'correas_alternador' => ['nullable', 'string', 'max:10'],
            'amortiguadores' => ['nullable', 'string', 'max:10'],
            'precalentador_estado_inicial' => ['nullable', 'string', 'max:10'],
            'bateria' => ['nullable', 'string', 'max:10'],
            'nivel_electrolito' => ['nullable', 'string', 'max:10'],
            'voltaje_bateria_estado' => ['nullable', 'string', 'max:10'],
            'estado_cargador' => ['nullable', 'string', 'max:10'],
            'voltaje_cargador' => ['nullable', 'string', 'max:50'],
            'tipo_control' => ['nullable', 'string', 'max:100'],
            'voltaje_alternador' => ['nullable', 'string', 'max:50'],
            'conexiones_control' => ['nullable', 'string', 'max:10'],
            'conexiones_potencia' => ['nullable', 'string', 'max:10'],
            'limpieza_general' => ['nullable', 'string', 'max:10'],

            // Filtros y cantidades - nuevos campos
            'cantidad_filtro_aire' => ['nullable', 'string', 'max:50'],
            'referencia_filtro_aire' => ['nullable', 'string', 'max:100'],
            'cantidad_filtro_aceite' => ['nullable', 'string', 'max:50'],
            'referencia_filtro_aceite' => ['nullable', 'string', 'max:100'],
            'cantidad_filtro_combustible' => ['nullable', 'string', 'max:50'],
            'referencia_filtro_combustible' => ['nullable', 'string', 'max:100'],
            'cantidad_filtro_separador' => ['nullable', 'string', 'max:50'],
            'referencia_filtro_separador' => ['nullable', 'string', 'max:100'],
            'cantidad_filtro_agua' => ['nullable', 'string', 'max:50'],
            'referencia_filtro_agua' => ['nullable', 'string', 'max:100'],
            'cantidad_cantidad_aceite' => ['nullable', 'string', 'max:50'],
            'referencia_cantidad_aceite' => ['nullable', 'string', 'max:100'],

            // Fotos antes (archivos de imagen)
            'foto_uno_antes' => ['nullable', 'file', 'image', 'max:5120'], // 5MB
            'pie_foto_uno_antes' => ['nullable', 'string', 'max:255'],
            'foto_dos_antes' => ['nullable', 'file', 'image', 'max:5120'],
            'pie_foto_dos_antes' => ['nullable', 'string', 'max:255'],
            'foto_tres_antes' => ['nullable', 'file', 'image', 'max:5120'],
            'pie_foto_tres_antes' => ['nullable', 'string', 'max:255'],

            // Actividad realizada
            'actividad_realizada' => ['nullable', 'string'],

            // Pruebas con equipo operando - Motor
            'valor_presion_aceite' => ['nullable', 'string', 'max:50'],
            'cantidad_presion_aceite' => ['nullable', 'string', 'max:20'],
            'valor_temp_refrigerante' => ['nullable', 'string', 'max:50'],
            'cantidad_temp_refrigerante' => ['nullable', 'string', 'max:20'],
            'valor_temp_aceite' => ['nullable', 'string', 'max:50'],
            'cantidad_temp_aceite' => ['nullable', 'string', 'max:20'],
            'valor_temp_turbo' => ['nullable', 'string', 'max:50'],
            'cantidad_temp_turbo' => ['nullable', 'string', 'max:20'],
            'valor_rpm' => ['nullable', 'string', 'max:50'],
            'cantidad_rpm' => ['nullable', 'string', 'max:20'],
            'valor_voltaje_bateria' => ['nullable', 'string', 'max:50'],
            'cantidad_voltaje_bateria' => ['nullable', 'string', 'max:20'],
            'valor_caida_voltaje_bat' => ['nullable', 'string', 'max:50'],
            'cantidad_caida_voltaje_bat' => ['nullable', 'string', 'max:20'],

            // Generador - VAC Fases
            'vac_fases_l1_l2' => ['nullable', 'string', 'max:50'],
            'vac_fases_l2_l3' => ['nullable', 'string', 'max:50'],
            'vac_fases_l1_l3' => ['nullable', 'string', 'max:50'],

            // Generador - Amperios
            'amperios_l1' => ['nullable', 'string', 'max:50'],
            'amperios_l2' => ['nullable', 'string', 'max:50'],
            'amperios_l3' => ['nullable', 'string', 'max:50'],

            // Generador - VAC Fase N
            'vac_fase_n_l1n' => ['nullable', 'string', 'max:50'],
            'vac_fase_n_l2n' => ['nullable', 'string', 'max:50'],
            'vac_fase_n_l3n' => ['nullable', 'string', 'max:50'],

            // Generador - Potencia - HZ - FP
            'potencia' => ['nullable', 'string', 'max:50'],
            'hz' => ['nullable', 'string', 'max:50'],
            'fp' => ['nullable', 'string', 'max:50'],

            // Protecciones
            'baja_presion' => ['nullable', 'string', 'max:50'],
            'alta_temperatura' => ['nullable', 'string', 'max:50'],
            'bajo_nivel_refrigerante' => ['nullable', 'string', 'max:50'],
            'bajo_voltaje_ac' => ['nullable', 'string', 'max:50'],

            // Fotos durante (archivos de imagen)
            'foto_uno_durante' => ['nullable', 'file', 'image', 'max:5120'],
            'pie_foto_uno_durante' => ['nullable', 'string', 'max:255'],
            'foto_dos_durante' => ['nullable', 'file', 'image', 'max:5120'],
            'pie_foto_dos_durante' => ['nullable', 'string', 'max:255'],
            'foto_tres_durante' => ['nullable', 'file', 'image', 'max:5120'],
            'pie_foto_tres_durante' => ['nullable', 'string', 'max:255'],
            'foto_cuatro_durante' => ['nullable', 'file', 'image', 'max:5120'],
            'pie_foto_cuatro_durante' => ['nullable', 'string', 'max:255'],
            'foto_cinco_durante' => ['nullable', 'file', 'image', 'max:5120'],
            'pie_foto_cinco_durante' => ['nullable', 'string', 'max:255'],
            'foto_seis_durante' => ['nullable', 'file', 'image', 'max:5120'],
            'pie_foto_seis_durante' => ['nullable', 'string', 'max:255'],
            'foto_siete_durante' => ['nullable', 'file', 'image', 'max:5120'],
            'pie_foto_siete_durante' => ['nullable', 'string', 'max:255'],
            'foto_ocho_durante' => ['nullable', 'file', 'image', 'max:5120'],
            'pie_foto_ocho_durante' => ['nullable', 'string', 'max:255'],
            'foto_nueve_durante' => ['nullable', 'file', 'image', 'max:5120'],
            'pie_foto_nueve_durante' => ['nullable', 'string', 'max:255'],

            // Recomendaciones
            'recomendaciones' => ['nullable', 'string'],

            // Llegada y salida técnico
            'llegada_tecnico' => ['nullable', 'date'],
            'salida_tecnico' => ['nullable', 'date', 'after_or_equal:llegada_tecnico'],

            // Calificación de servicio
            'calificacion_servicio' => ['nullable', 'string', 'in:Bueno,Regular,Malo'],

            // Posición de los instrumentos
            'control' => ['nullable', 'string', 'max:10'],
            'transferencia' => ['nullable', 'string', 'max:10'],
            'posicion_cargador' => ['nullable', 'string', 'max:10'],
            'totalizador' => ['nullable', 'string', 'max:10'],
            'precalentador_posicion' => ['nullable', 'string', 'max:10'],

            // Fotos después (archivos de imagen)
            'foto_uno_despues' => ['nullable', 'file', 'image', 'max:5120'],
            'pie_foto_uno_despues' => ['nullable', 'string', 'max:255'],
            'foto_dos_despues' => ['nullable', 'file', 'image', 'max:5120'],
            'pie_foto_dos_despues' => ['nullable', 'string', 'max:255'],
            'foto_tres_despues' => ['nullable', 'file', 'image', 'max:5120'],
            'pie_foto_tres_despues' => ['nullable', 'string', 'max:255'],

            // Firmas (almacenadas como base64 data URLs)
            'firma_tecnico' => ['nullable', 'string'],
            'nombre_tecnico' => ['nullable', 'string', 'max:100'],
            'cedula_tecnico' => ['nullable', 'string', 'max:50'],

            'firma_cliente' => ['nullable', 'string'],
            'nombre_cliente' => ['nullable', 'string', 'max:100'],
            'cedula_cliente' => ['nullable', 'string', 'max:50'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'tipo_servicio.required' => 'El tipo de servicio es obligatorio.',
            'tipo_servicio.in' => 'El tipo de servicio debe ser: Mantenimiento, Servicio, Inspección, Soporte o Emergencia.',
            'salida_tecnico.after_or_equal' => 'La fecha de salida debe ser posterior o igual a la fecha de llegada.',
            'calificacion_servicio.in' => 'La calificación debe ser: Bueno, Regular o Malo.',
            '*.image' => 'El archivo debe ser una imagen.',
            '*.max' => 'El archivo no debe superar los 5MB.',
        ];
    }
}
