<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $employeeId = $this->route('employee')?->id ?? null;

        return [
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'dni' => [
                'required',
                'string',
                'size:8',
                Rule::unique('employees', 'dni')->ignore($employeeId),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('employees', 'email')->ignore($employeeId),
            ],
            'telefono' => 'nullable|string|max:20',
            'fecha_nacimiento' => 'nullable|date',
            'direccion' => 'nullable|string|max:255',
            'cargo' => 'nullable|string|max:100',
            'nivel' => 'nullable|string|max:100',
            'sueldo_basico' => 'required|numeric|min:0',
            'fecha_ingreso' => 'nullable|date',
            'fecha_cese' => 'nullable|date|after:fecha_ingreso',
            'regimen_laboral' => 'nullable|string|max:150',
            'regimen_pensionario' => 'nullable|in:AFP,ONP,NO_APORTE',
            'cuspp' => 'nullable|string|max:20',
            'banco' => 'nullable|string|max:50',
            'cuenta_bancaria' => 'nullable|string|max:50',
            'numero_cts' => 'nullable|string|max:50',
            'meta' => 'nullable|string|max:50',
            'rubro' => 'nullable|string|max:100',
            'tiene_asignacion_familiar' => 'nullable|boolean',
            'asignacion_familiar_porcentaje' => 'nullable|numeric|min:0|max:100',
            'asignacion_familiar_monto' => 'nullable|numeric|min:0',
            'bonificacion' => 'nullable|numeric|min:0',
            'documento_ingreso' => 'nullable|string|max:255',
            'documento_path' => 'nullable|string|max:255',
            'documento_archivo' => 'nullable|file|max:5120',
            'eliminar_documento' => 'nullable|boolean',
            'conformidad_tregistro' => 'nullable|boolean',
            'activo' => 'nullable|boolean',
            'afp_especifica' => 'nullable|in:HABITAT,INTEGRA,PRIMA,PROFUTURO',
            'afp_tipo_comision' => 'nullable|in:FLUJO,MIXTA',
            'afp_tiene_jubilacion' => 'nullable|boolean',
            'aplica_renta_quinta' => 'nullable|boolean',
            'renta_quinta_porcentaje' => 'nullable|numeric|min:0|max:100',
            'renta_quinta_uit' => 'nullable|numeric|min:0',
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
            'email.required' => 'El campo correo electrónico es obligatorio.',
            'email.email' => 'Ingrese un correo electrónico válido.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'sueldo_basico.required' => 'El campo sueldo básico es obligatorio.',
            'sueldo_basico.numeric' => 'El sueldo básico debe ser un número.',
            'fecha_cese.after' => 'La fecha de cese debe ser posterior a la fecha de ingreso.',
            'documento_archivo.max' => 'El archivo no debe exceder 5MB.',
        ];
    }
}
