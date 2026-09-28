<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayrollRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'periodo' => 'required|string',
            'sueldo_basico' => 'required|numeric|min:0',
            'asignacion_familiar' => 'nullable|numeric|min:0',
            'horas_extras_horas' => 'nullable|numeric|min:0',
            'monto_horas_extras' => 'nullable|numeric|min:0',
            'faltas' => 'nullable|integer|min:0',
            'monto_faltas' => 'nullable|numeric|min:0',
            'tardanzas' => 'nullable|integer|min:0',
            'monto_tardanzas' => 'nullable|numeric|min:0',
            'remuneracion_bruta' => 'nullable|numeric|min:0',
            'bonificaciones_no_remunerativas' => 'nullable|numeric|min:0',
            'sistema_pensiones' => 'nullable|in:AFP,ONP,NO_APORTE',
            'retencion_pensiones' => 'nullable|numeric|min:0',
            'renta_quinta' => 'nullable|numeric|min:0',
            'neto_pagar' => 'nullable|numeric|min:0',
            'estado' => 'nullable|in:GENERADA,PAGADA,ANULADA',
        ];
    }
}
