<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfigurationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo_navegacion' => 'required|in:header,sidebar',
            'empresa_nombre' => 'required|string|max:255',
            'empresa_nombre_corto' => 'required|string|max:255',
            'empresa_ruc' => 'required|string|digits:11',
            'empresa_direccion' => 'required|string|max:255',
            'empresa_telefono' => 'required|string|max:50',
            'empresa_email' => 'required|email|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'logo_default' => 'nullable|boolean',
            'fondo_login' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'fondo_default' => 'nullable|boolean',
            'horario_entrada' => 'required|date_format:H:i',
            'horario_salida' => 'required|date_format:H:i',
            'horario_entrada_manana' => 'required|date_format:H:i',
            'horario_salida_manana' => 'required|date_format:H:i',
            'horario_entrada_tarde' => 'required|date_format:H:i',
            'horario_salida_tarde' => 'required|date_format:H:i',
            'tolerancia_manana' => 'required|integer|min:0',
            'tolerancia_tarde' => 'required|integer|min:0',
            'essalud_tasa' => 'required|numeric|min:0|max:100',
            'rmv' => 'required|numeric|min:0',
            'sctr_salud_tasa' => 'required|numeric|min:0|max:100',
            'sctr_pensiones_tasa' => 'required|numeric|min:0|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'tipo_navegacion.required' => 'Debes elegir el tipo de navegación.',
            'tipo_navegacion.in' => 'El tipo de navegación no es válido.',
            'empresa_nombre.required' => 'El nombre de la empresa es obligatorio.',
            'empresa_ruc.required' => 'El RUC es obligatorio.',
            'empresa_ruc.digits' => 'El RUC debe tener 11 dígitos.',
            'empresa_direccion.required' => 'La dirección es obligatoria.',
            'empresa_telefono.required' => 'El teléfono es obligatorio.',
            'empresa_email.required' => 'El correo es obligatorio.',
            'empresa_email.email' => 'El correo no tiene un formato válido.',
            'logo.image' => 'El logo debe ser una imagen.',
            'logo.mimes' => 'El logo debe ser JPG, PNG o WEBP.',
            'logo.max' => 'El logo no debe superar 2 MB.',
            'fondo_login.image' => 'El fondo debe ser una imagen.',
            'fondo_login.mimes' => 'El fondo debe ser JPG, PNG o WEBP.',
            'fondo_login.max' => 'El fondo no debe superar 4 MB.',
            'horario_entrada.required' => 'La hora de entrada es obligatoria.',
            'horario_salida.required' => 'La hora de salida es obligatoria.',
            '*.date_format' => 'El formato debe ser HH:MM.',
            'tolerancia_manana.integer' => 'Los minutos de tolerancia deben ser un número.',
            'tolerancia_tarde.integer' => 'Los minutos de tolerancia deben ser un número.',
        ];
    }
}