<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSolicitudRequest extends FormRequest
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
            'client_id' => ['required', 'exists:clients,id'],
            'sucursal_id' => ['required', 'exists:sucursals,id'],
            'equipo_id' => ['required', 'exists:equipos,id'],
            'user_id' => ['required', 'exists:users,id'],
            'fecha_programada' => ['required', 'date'],
            'quien_solicita' => ['required', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'mail' => ['nullable', 'email', 'max:255'],
            'ubicacion' => ['required', 'string', 'max:255'],
            'prioridad' => ['required', 'in:Normal,Intermedio,Urgente'],
            'detalles' => ['nullable', 'string'],
            'estado' => ['required', 'in:Nueva,Proceso,Revisión,Finalizada,Anulada,Programada'],
            'actividad' => ['required', 'string', 'max:255'],
            'mantenimiento_id' => ['nullable', 'exists:mantenimientos,id'],
            'fecha_mantenimiento' => ['nullable', 'date'],
            'orden_trabajo' => ['nullable', 'file'],
        ];
    }
}
