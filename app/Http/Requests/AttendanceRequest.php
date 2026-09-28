<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'employee_id' => 'required|exists:employees,id',
            'fecha' => 'required|date',
            'turno' => 'required|in:MANANA,TARDE,COMPLETO',
            'tipo_licencia' => 'nullable|in:CG,SH,ME,PA,PN',
            'fecha_fin_licencia' => 'nullable|date|after_or_equal:fecha',
            'observacion' => 'nullable|string|max:500',
        ];

        $horaEntradaRequired = empty($this->tipo_licencia);

        if ($horaEntradaRequired) {
            $rules['hora_entrada'] = 'required|date_format:H:i';
            $rules['hora_salida'] = 'nullable|date_format:H:i|after:hora_entrada';
        } else {
            $rules['hora_entrada'] = 'nullable|date_format:H:i';
            $rules['hora_salida'] = 'nullable|date_format:H:i';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'El empleado es obligatorio.',
            'fecha.required' => 'La fecha es obligatoria.',
            'turno.required' => 'El turno es obligatorio.',
            'hora_entrada.required' => 'La hora de entrada es obligatoria (o selecciona un tipo de licencia).',
            'hora_salida.after' => 'La hora de salida debe ser mayor a la hora de entrada.',
            'fecha_fin_licencia.after_or_equal' => 'La fecha fin de licencia debe ser igual o posterior a la fecha de inicio.',
        ];
    }
}