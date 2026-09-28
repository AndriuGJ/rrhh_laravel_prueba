<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Configuration;
use App\Models\Employee;
use Carbon\Carbon;

class AttendanceService
{
    public function calcularDescuento(Attendance $attendance): float
    {
        $employee = $attendance->employee;
        if (! $employee) {
            return 0;
        }

        $sueldo = (float) $employee->sueldo_basico;

        if ($attendance->estado === 'TARDE' && $attendance->minutos_tardanza > 0) {
            return $this->calcularTardanza($employee, $attendance->minutos_tardanza);
        }

        if ($attendance->estado === 'FALTA') {
            if ($attendance->es_falta_turno) {
                return $this->calcularFaltaTurno($employee);
            }

            return $this->calcularFaltaCompleta($employee);
        }

        return 0;
    }

    public function calcularTardanza(Employee $employee, int $minutosTardanza): float
    {
        if ($minutosTardanza <= 0) {
            return 0;
        }

        $sueldo = (float) $employee->sueldo_basico;
        $minutoValue = $sueldo / 14400;

        return round($minutoValue * $minutosTardanza, 2);
    }

    public function calcularFaltaTurno(Employee $employee): float
    {
        $sueldo = (float) $employee->sueldo_basico;

        return round($sueldo / 60, 2);
    }

    public function calcularFaltaCompleta(Employee $employee): float
    {
        $sueldo = (float) $employee->sueldo_basico;

        return round($sueldo / 30, 2);
    }

    public function determinarEstado(?string $horaEntrada, string $turno): array
    {
        $entradaManana = Configuration::get('horario_entrada_manana', '08:00');
        $toleranciaManana = (int) Configuration::get('tolerancia_manana', 5);
        $entradaTarde = Configuration::get('horario_entrada_tarde', '14:00');

        $horaEntradaMinutos = $horaEntrada ? Carbon::parse($horaEntrada)->format('Hi') : null;
        $entradaMananaMinutos = Carbon::parse($entradaManana)->format('Hi');
        $toleranciaMananaMinutos = $toleranciaManana;

        if ($turno === 'MANANA' && $horaEntradaMinutos) {
            $hora = (int) $horaEntradaMinutos;
            $limite = (int) $entradaMananaMinutos;
            $tarde = $limite + $toleranciaMananaMinutos;

            if ($hora <= $limite) {
                return ['estado' => 'PUNTUAL', 'minutos_tardanza' => 0];
            } elseif ($hora <= $tarde) {
                $minutos = $hora - $limite;

                return ['estado' => 'TARDE', 'minutos_tardanza' => $minutos];
            } else {
                return ['estado' => 'FALTA', 'minutos_tardanza' => 0, 'es_falta_turno' => true];
            }
        }

        if ($turno === 'TARDE' && $horaEntradaMinutos) {
            $hora = (int) $horaEntradaMinutos;
            $limite = (int) Carbon::parse($entradaTarde)->format('Hi');

            if ($hora <= $limite) {
                return ['estado' => 'PUNTUAL', 'minutos_tardanza' => 0];
            } else {
                return ['estado' => 'FALTA', 'minutos_tardanza' => 0, 'es_falta_turno' => true];
            }
        }

        if ($turno === 'COMPLETO' && $horaEntradaMinutos) {
            $entradaCompleto = Configuration::get('horario_entrada', '08:00');
            $toleranciaCompleto = (int) Configuration::get('tolerancia_manana', 5);

            $hora = (int) $horaEntradaMinutos;
            $limite = (int) Carbon::parse($entradaCompleto)->format('Hi');
            $tarde = $limite + $toleranciaCompleto;

            if ($hora <= $limite) {
                return ['estado' => 'PUNTUAL', 'minutos_tardanza' => 0];
            } elseif ($hora <= $tarde) {
                $minutos = $hora - $limite;

                return ['estado' => 'TARDE', 'minutos_tardanza' => $minutos];
            } else {
                return ['estado' => 'FALTA', 'minutos_tardanza' => 0, 'es_falta_turno' => true];
            }
        }

        return ['estado' => 'FALTA', 'minutos_tardanza' => 0];
    }
}
