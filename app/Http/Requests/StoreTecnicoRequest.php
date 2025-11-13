<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTecnicoRequest extends FormRequest
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
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'identificacion' => ['required', 'string', 'max:255', 'min:8', 'unique:tecnicos,identificacion'],
            'correo' => ['required', 'email', 'max:255', 'unique:tecnicos,correo', 'unique:users,email'],
            'nombre_completo' => ['required', 'string', 'max:255'],
            'persona_contacto' => ['nullable', 'string', 'max:255'],
            'telefono_contacto' => ['nullable', 'string', 'max:20'],
            'direccion_contacto' => ['nullable', 'string', 'max:500'],
            'tipo_sangre' => ['nullable', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
            'eps' => ['nullable', 'string', 'max:255'],
            'fecha_nacimiento' => ['nullable', 'date', 'before:today'],
            'fecha_inicio_contrato' => ['required', 'date'],
            'fecha_fin_contrato' => ['nullable', 'date', 'after:fecha_inicio_contrato'],
            'tipo_contrato' => ['required', 'in:Indefinido,Fijo,Obra o labor,Prestación de servicios'],
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
            'foto.image' => 'El archivo debe ser una imagen.',
            'foto.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
            'foto.max' => 'La imagen no debe ser mayor a 2MB.',
            'identificacion.required' => 'La identificación es obligatoria.',
            'identificacion.unique' => 'Esta identificación ya está registrada.',
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'El correo electrónico debe ser válido.',
            'correo.unique' => 'Este correo electrónico ya está registrado.',
            'nombre_completo.required' => 'El nombre completo es obligatorio.',
            'tipo_sangre.in' => 'El tipo de sangre debe ser uno de los siguientes: A+, A-, B+, B-, AB+, AB-, O+, O-.',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'fecha_inicio_contrato.required' => 'La fecha de inicio del contrato es obligatoria.',
            'fecha_fin_contrato.after' => 'La fecha de fin del contrato debe ser posterior a la fecha de inicio.',
            'tipo_contrato.required' => 'El tipo de contrato es obligatorio.',
            'tipo_contrato.in' => 'El tipo de contrato debe ser uno de los siguientes: Indefinido, Fijo, Obra o labor, Prestación de servicios.',
        ];
    }
}
