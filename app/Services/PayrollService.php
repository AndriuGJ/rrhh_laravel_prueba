<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Configuration;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\PayrollIncome;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    public function __construct(
        protected EmployeeCalculationService $employeeCalculation,
        protected AttendanceService $attendanceService
    ) {}

    public function generarPlanilla(Employee $employee, string $periodo): Payroll
    {
        $attendances = Attendance::where('employee_id', $employee->id)
            ->where('fecha', 'like', $periodo.'%')
            ->get();

        $sueldoBasico = (float) $employee->sueldo_basico;
        $asignacionFamiliar = $this->employeeCalculation->calcularAsignacionFamiliar($employee);

        $bonificacionesRemunerativas = 0;
        $bonificacionesNoRemunerativas = 0;

        $bonuses = DB::table('employee_bonuses')
            ->join('bonus_types', 'employee_bonuses.bonus_type_id', '=', 'bonus_types.id')
            ->where('employee_bonuses.employee_id', $employee->id)
            ->select('employee_bonuses.monto', 'bonus_types.es_remunerativo', 'bonus_types.nombre')
            ->get();

        $bonusesData = [];
        foreach ($bonuses as $eb) {
            if ($eb->es_remunerativo) {
                $bonificacionesRemunerativas += (float) $eb->monto;
            } else {
                $bonificacionesNoRemunerativas += (float) $eb->monto;
            }
            $bonusesData[] = [
                'monto' => (float) $eb->monto,
                'es_remunerativo' => $eb->es_remunerativo,
                'nombre' => $eb->nombre,
            ];
        }

        $licenciasFechas = $attendances->whereNotNull('tipo_licencia')->pluck('fecha')->map(function ($f) {
            return is_string($f) ? $f : $f->format('Y-m-d');
        })->toArray();

        $tardanzas = $attendances->where('estado', 'TARDE')->sum('minutos_tardanza');
        
        $faltasCompletas = $attendances->where('estado', 'FALTA')
            ->where('es_falta_turno', false)
            ->whereNull('tipo_licencia')
            ->count();
            
        $faltasTurno = $attendances->where('estado', 'FALTA')
            ->where('es_falta_turno', true)
            ->whereNull('tipo_licencia')
            ->count();

        $montoTardanzas = round(($sueldoBasico / 14400) * $tardanzas, 2);
        $montoFaltas = round(($sueldoBasico / 30) * $faltasCompletas, 2);
        $montoFaltasTurno = round(($sueldoBasico / 60) * $faltasTurno, 2);
        $montoFaltas += $montoFaltasTurno;

        $horasExtras = 0;
        $montoHorasExtras = 0;

        $descuentos = $montoTardanzas + $montoFaltas;
        $remuneracionBruta = $sueldoBasico + $asignacionFamiliar + $bonificacionesRemunerativas + $montoHorasExtras - $descuentos;

        $essalud = $this->calcularEssalud($remuneracionBruta);
        $sctrSalud = $this->calcularSctr($remuneracionBruta, 'sctr_salud_tasa');
        $sctrPensiones = $this->calcularSctr($remuneracionBruta, 'sctr_pensiones_tasa');
        $totalAportesEmpleador = $essalud + $sctrSalud + $sctrPensiones;

        $porcentajePension = $this->employeeCalculation->calcularPorcentajePension($employee);
        $retencion = round($remuneracionBruta * ($porcentajePension / 100), 2);

        $rentaQuinta = $this->employeeCalculation->calcularRentaQuinta($employee);

        $totalIngresos = $sueldoBasico + $asignacionFamiliar + $bonificacionesRemunerativas + $bonificacionesNoRemunerativas + $montoHorasExtras;
        $totalDescuentos = $descuentos + $retencion + $rentaQuinta;
        $netoPagar = ($remuneracionBruta - $retencion) + $bonificacionesNoRemunerativas - $rentaQuinta;

        $payroll = self::create([
            'employee_id' => $employee->id,
            'periodo' => $periodo,
            'sueldo_basico' => $sueldoBasico,
            'asignacion_familiar' => $asignacionFamiliar,
            'horas_extras_horas' => $horasExtras,
            'monto_horas_extras' => $montoHorasExtras,
            'faltas' => $faltasCompletas + $faltasTurno,
            'monto_faltas' => $montoFaltas,
            'tardanzas' => $tardanzas,
            'monto_tardanzas' => $montoTardanzas,
            'remuneracion_bruta' => $remuneracionBruta,
            'essalud' => $essalud,
            'sctr_salud' => $sctrSalud,
            'sctr_pensiones' => $sctrPensiones,
            'total_aportes_empleador' => $totalAportesEmpleador,
            'bonificaciones_remunerativas' => $bonificacionesRemunerativas,
            'bonificaciones_no_remunerativas' => $bonificacionesNoRemunerativas,
            'sistema_pensiones' => $employee->regimen_pensionario,
            'retencion_pensiones' => $retencion,
            'renta_quinta' => $rentaQuinta,
            'total_ingresos' => $totalIngresos,
            'total_descuentos' => $totalDescuentos,
            'neto_pagar' => $netoPagar,
            'estado' => 'GENERADA',
            'estado_registro' => true,
        ]);

        foreach ($bonusesData as $bonif) {
            PayrollIncome::create([
                'payroll_id' => $payroll->id,
                'concepto' => 'BONIFICACION: '.$bonif['nombre'],
                'monto' => $bonif['monto'],
                'tipo' => $bonif['es_remunerativo'] ? 'COMPUTABLE' : 'NO_COMPUTABLE',
            ]);
        }

        return $payroll;
    }

    protected function calcularEssalud(float $remuneracionBruta): float
    {
        $rmv = (float) Configuration::get('rmv', 1025);
        $tasa = (float) Configuration::get('essalud_tasa', 9);

        $baseImponible = max($remuneracionBruta, $rmv);

        return round($baseImponible * ($tasa / 100), 2);
    }

    protected function calcularSctr(float $remuneracionBruta, string $claveTasa): float
    {
        $tasa = (float) Configuration::get($claveTasa, 0.8);

        return round($remuneracionBruta * ($tasa / 100), 2);
    }

    protected static function create(array $data): Payroll
    {
        return Payroll::create($data);
    }
}