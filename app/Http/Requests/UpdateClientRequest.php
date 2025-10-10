<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
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
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:100|unique:clients,email,' . $this->client->id,
            'phone_number' => 'nullable|string|max:20|unique:clients,phone_number,' . $this->client->id,
            'password' => 'nullable|string|min:8',
            'enterprise_name' => 'nullable|string|max:255',
            'nit' => 'nullable|string|max:50',
        ];
    }
}
