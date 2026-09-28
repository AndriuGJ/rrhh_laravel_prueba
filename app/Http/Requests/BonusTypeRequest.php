<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BonusTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $bonusTypeId = $this->route('bonification')?->id ?? null;

        return [
            'nombre' => [
                'required',
                'string',
                'max:150',
                Rule::unique('bonus_types', 'nombre')->ignore($bonusTypeId),
            ],
            'descripcion' => 'nullable|string|max:255',
            'es_remunerativo' => 'required|boolean',
            'estado' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la bonificación es obligatorio.',
            'nombre.max' => 'El nombre no debe superar 150 caracteres.',
            'nombre.unique' => 'Ya existe una bonificación con ese nombre.',
            'descripcion.max' => 'La descripción no debe superar 255 caracteres.',
            'es_remunerativo.required' => 'Debes indicar si es remunerativa o no.',
            'estado.required' => 'Debes indicar el estado.',
        ];
    }
}
