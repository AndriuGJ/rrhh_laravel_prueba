<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegimenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $regimenId = $this->route('regimen')?->id ?? null;

        return [
            'nombre' => [
                'required',
                'string',
                'max:150',
                Rule::unique('regimens', 'nombre')->ignore($regimenId),
            ],
            'descripcion' => 'nullable|string|max:255',
            'estado' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del régimen es obligatorio.',
            'nombre.max' => 'El nombre no debe superar 150 caracteres.',
            'nombre.unique' => 'Ya existe un régimen con ese nombre.',
            'descripcion.max' => 'La descripción no debe superar 255 caracteres.',
            'estado.required' => 'Debes indicar el estado.',
        ];
    }
}
