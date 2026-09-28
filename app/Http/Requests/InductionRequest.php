<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InductionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $inductionId = $this->route('induction');

        return [
            'employee_id' => 'required|exists:employees,id',
            'tipo' => 'required|in:GENERAL,ESPECIFICA,SEGURIDAD',
            'responsable' => 'nullable|string|max:100',
            'fecha' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'tipo.required' => 'El campo tipo es obligatorio.',
            'tipo.in' => 'El tipo debe ser GENERAL, ESPECIFICA o SEGURIDAD.',
            'employee_id.required' => 'El empleado es obligatorio.',
            'employee_id.exists' => 'El empleado no existe.',
        ];
    }
}