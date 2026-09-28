<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DependentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $dependentId = $this->route('dependent')?->id 
            ?? $this->input('id') 
            ?? null;

        return [
            'employee_id' => 'required|exists:employees,id',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'dni' => [
                'required',
                'string',
                'size:8',
                Rule::unique('dependents', 'dni')->ignore($dependentId)
            ],
            'tipo_vinculo' => 'required|in:H,C,P,M,PE,HM',
            'fecha_nacimiento' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'nombres.required' => 'El campo nombres es obligatorio.',
            'apellidos.required' => 'El campo apellidos es obligatorio.',
            'dni.required' => 'El campo DNI es obligatorio.',
            'dni.size' => 'El DNI debe tener exactamente 8 dígitos.',
            'dni.unique' => 'El DNI ya está registrado.',
            'tipo_vinculo.required' => 'El campo tipo de vínculo es obligatorio.',
            'employee_id.required' => 'El empleado es obligatorio.',
            'employee_id.exists' => 'El empleado no existe.',
        ];
    }
}