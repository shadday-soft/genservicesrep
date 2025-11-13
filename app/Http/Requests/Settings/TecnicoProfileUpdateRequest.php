<?php

namespace App\Http\Requests\Settings;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TecnicoProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->role === 'Tecnico';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $tecnico = $this->user()->tecnico;

        return [
            'nombre_completo' => ['required', 'string', 'max:255'],
            'identificacion' => ['required', 'string', 'max:255', 'min:8', Rule::unique('tecnicos')->ignore($tecnico?->id)],
            'correo' => ['required', 'email', 'max:255', Rule::unique('tecnicos')->ignore($tecnico?->id), Rule::unique('users', 'email')->ignore($this->user()->id)],
            'persona_contacto' => ['nullable', 'string', 'max:255'],
            'telefono_contacto' => ['nullable', 'string', 'max:20'],
            'direccion_contacto' => ['nullable', 'string', 'max:500'],
        ];
    }
}
