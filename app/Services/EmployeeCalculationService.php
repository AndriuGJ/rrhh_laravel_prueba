<?php

namespace App\Services;

use App\Models\Employee;

class EmployeeCalculationService
{
    public function calcularAsignacionFamiliar(Employee $employee): float
    {
        if (! $employee->tiene_asignacion_familiar) {
            return 0;
        }

        $monto = $employee->asignacion_familiar_monto ?? 0;
        $porcentaje = $employee->asignacion_familiar_porcentaje ?? 0;

        if ($porcentaje > 0 && $monto > 0) {
            return $monto * ($porcentaje / 100);
        }

        return $monto;
    }

    public function calcularAFP(Employee $employee): float
    {
        if ($employee->regimen_pensionario !== 'AFP') {
            return 0;
        }

        $sueldo = $employee->sueldo_basico ?? 0;
        $obligatorio = $sueldo * 0.10;
        $prima = $employee->afp_tiene_jubilacion ? 0 : $sueldo * 0.0137;
        $comision = 0;

        if ($employee->afp_tipo_comision === 'FLUJO') {
            $comision = $sueldo * match ($employee->afp_especifica) {
                'HABITAT' => 0.0147,
                'INTEGRA' => 0.0155,
                'PRIMA' => 0.0160,
                'PROFUTURO' => 0.0169,
                default => 0,
            };
        }

        return $obligatorio + $prima + $comision;
    }

    public function calcularONP(Employee $employee): float
    {
        return ($employee->sueldo_basico ?? 0) * 0.13;
    }

    public function calcularRentaQuinta(Employee $employee): float
    {
        if (! $employee->aplica_renta_quinta) {
            return 0;
        }

        $sueldo = $employee->sueldo_basico ?? 0;

        if ($sueldo < 2750) {
            return 0;
        }

        $porcentaje = $employee->renta_quinta_porcentaje ?? 8;
        $uit = $employee->renta_quinta_uit ?? 38500;

        $ingresoAnual = $sueldo * 14;
        $base = $ingresoAnual - $uit;

        if ($base <= 0) {
            return 0;
        }

        return ($base * ($porcentaje / 100)) / 12;
    }

    public function calcularPorcentajePension(Employee $employee): float
    {
        $porcentaje = 0;

        if ($employee->regimen_pensionario === 'ONP') {
            $porcentaje = 13;
        } elseif ($employee->regimen_pensionario === 'AFP') {
            $porcentaje = 10;

            if (! $employee->afp_tiene_jubilacion) {
                $porcentaje += 1.37;
            }

            if ($employee->afp_tipo_comision === 'FLUJO') {
                $porcentaje += match ($employee->afp_especifica) {
                    'HABITAT' => 1.47,
                    'INTEGRA' => 1.55,
                    'PRIMA' => 1.60,
                    'PROFUTURO' => 1.69,
                    default => 0,
                };
            }
        }

        return $porcentaje;
    }
}
