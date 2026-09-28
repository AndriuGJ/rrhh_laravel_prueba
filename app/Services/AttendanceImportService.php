<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Configuration;
use App\Models\Employee;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AttendanceImportService
{
    protected $resultado = [
        'total' => 0,
        'creados' => 0,
        'actualizados' => 0,
        'errores' => [],
    ];

    public function importarExcel($archivo): array
    {
        $datos = $this->leerExcel($archivo);

        foreach ($datos as $fila) {
            $this->procesarFila($fila);
        }

        return $this->resultado;
    }

    protected function leerExcel($archivo): array
    {
        $datos = [];

        try {
            $spreadsheet = IOFactory::load($archivo);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
            array_shift($rows);

            foreach ($rows as $row) {
                $datos[] = [
                    'id' => $row[0] ?? null,
                    'nombre' => $row[1] ?? null,
                    'fecha_m1' => $row[2] ?? null,
                    'entrada_m1' => $row[3] ?? null,
                    'fecha_m2' => $row[4] ?? null,
                    'salida_m2' => $row[5] ?? null,
                    'fecha_t1' => $row[6] ?? null,
                    'entrada_t1' => $row[7] ?? null,
                    'fecha_t2' => $row[8] ?? null,
                    'salida_t2' => $row[9] ?? null,
                ];
            }
        } catch (\Exception $e) {
            $this->resultado['errores'][] = 'Error al leer Excel: '.$e->getMessage();
        }

        return $datos;
    }

    protected function procesarFila(array $datos): void
    {
        $this->resultado['total']++;

        $nombre = trim($datos['nombre'] ?? '');

        if (empty($nombre)) {
            $this->resultado['errores'][] = "Fila {$this->resultado['total']}: Nombre de empleado vacío";

            return;
        }

        $empleado = $this->buscarEmpleadoPorNombre($nombre);

        if (! $empleado) {
            $this->resultado['errores'][] = "Fila {$this->resultado['total']}: Empleado '{$nombre}' no encontrado";

            return;
        }

        $this->procesarTurnoManana($empleado, $datos);
        $this->procesarTurnoTarde($empleado, $datos);
    }

    protected function buscarEmpleadoPorNombre(string $nombre): ?Employee
    {
        $nombreLower = mb_strtolower($nombre, 'UTF-8');
        $palabras = preg_split('/\s+/', $nombreLower);

        $empleados = Employee::all();

        foreach ($empleados as $emp) {
            $nombresLower = mb_strtolower($emp->nombres, 'UTF-8');
            $apellidosLower = mb_strtolower($emp->apellidos, 'UTF-8');

            $coincidencias = 0;
            foreach ($palabras as $palabra) {
                if (! empty($palabra) && (
                    stripos($nombresLower, $palabra) !== false ||
                    stripos($apellidosLower, $palabra) !== false
                )) {
                    $coincidencias++;
                }
            }

            if ($coincidencias >= count($palabras) - 1 && $coincidencias > 0) {
                return $emp;
            }
        }

        return null;
    }

    protected function procesarTurnoManana(Employee $empleado, array $datos): void
    {
        $fechaM1 = $this->parsearFecha($datos['fecha_m1']);
        $entradaM1 = $datos['entrada_m1'];

        if ($fechaM1 && ! empty($entradaM1)) {
            $estado = $this->determinarEstadoManana($entradaM1);

            $attendance = Attendance::updateOrCreate(
                [
                    'employee_id' => $empleado->id,
                    'fecha' => $fechaM1,
                    'turno' => 'MANANA',
                ],
                [
                    'hora_entrada' => $entradaM1,
                    'hora_salida' => $datos['salida_m2'] ?? null,
                    'estado' => $estado['estado'],
                    'minutos_tardanza' => $estado['minutos_tardanza'],
                    'es_falta_turno' => $estado['es_falta_turno'] ?? false,
                    'estado_registro' => true,
                ]
            );

            if ($attendance->wasRecentlyCreated) {
                $this->resultado['creados']++;
            } else {
                $this->resultado['actualizados']++;
            }
        } elseif ($fechaM1) {
            $attendance = Attendance::updateOrCreate(
                [
                    'employee_id' => $empleado->id,
                    'fecha' => $fechaM1,
                    'turno' => 'MANANA',
                ],
                [
                    'hora_entrada' => null,
                    'hora_salida' => null,
                    'estado' => 'FALTA',
                    'minutos_tardanza' => 0,
                    'es_falta_turno' => true,
                    'estado_registro' => true,
                ]
            );

            if ($attendance->wasRecentlyCreated) {
                $this->resultado['creados']++;
            } else {
                $this->resultado['actualizados']++;
            }
        }
    }

    protected function procesarTurnoTarde(Employee $empleado, array $datos): void
    {
        $fechaT1 = $this->parsearFecha($datos['fecha_t1']);
        $entradaT1 = $datos['entrada_t1'];

        if ($fechaT1 && ! empty($entradaT1)) {
            $estado = $this->determinarEstadoTarde($entradaT1);

            $attendance = Attendance::updateOrCreate(
                [
                    'employee_id' => $empleado->id,
                    'fecha' => $fechaT1,
                    'turno' => 'TARDE',
                ],
                [
                    'hora_entrada' => $entradaT1,
                    'hora_salida' => $datos['salida_t2'] ?? null,
                    'estado' => $estado['estado'],
                    'minutos_tardanza' => $estado['minutos_tardanza'],
                    'es_falta_turno' => $estado['es_falta_turno'] ?? false,
                    'estado_registro' => true,
                ]
            );

            if ($attendance->wasRecentlyCreated) {
                $this->resultado['creados']++;
            } else {
                $this->resultado['actualizados']++;
            }
        } elseif ($fechaT1) {
            $attendance = Attendance::updateOrCreate(
                [
                    'employee_id' => $empleado->id,
                    'fecha' => $fechaT1,
                    'turno' => 'TARDE',
                ],
                [
                    'hora_entrada' => null,
                    'hora_salida' => null,
                    'estado' => 'FALTA',
                    'minutos_tardanza' => 0,
                    'es_falta_turno' => true,
                    'estado_registro' => true,
                ]
            );

            if ($attendance->wasRecentlyCreated) {
                $this->resultado['creados']++;
            } else {
                $this->resultado['actualizados']++;
            }
        }
    }

    protected function determinarEstadoManana(?string $hora): array
    {
        if (empty($hora)) {
            return ['estado' => 'FALTA', 'minutos_tardanza' => 0, 'es_falta_turno' => true];
        }

        try {
            $horaFormato = Carbon::parse($hora)->format('Hi');
            $entradaManana = Configuration::get('horario_entrada_manana', '08:00');
            $toleranciaManana = (int) Configuration::get('tolerancia_manana', 5);

            $horaInt = (int) $horaFormato;
            $limiteInt = (int) str_replace(':', '', $entradaManana);
            $tardeInt = $limiteInt + $toleranciaManana;

            if ($horaInt <= $limiteInt) {
                return ['estado' => 'PUNTUAL', 'minutos_tardanza' => 0, 'es_falta_turno' => false];
            } elseif ($horaInt <= $tardeInt) {
                $minutos = $horaInt - $limiteInt;

                return ['estado' => 'TARDE', 'minutos_tardanza' => $minutos, 'es_falta_turno' => false];
            } else {
                return ['estado' => 'FALTA', 'minutos_tardanza' => 0, 'es_falta_turno' => true];
            }
        } catch (\Exception $e) {
            return ['estado' => 'FALTA', 'minutos_tardanza' => 0, 'es_falta_turno' => true];
        }
    }

    protected function determinarEstadoTarde(?string $hora): array
    {
        if (empty($hora)) {
            return ['estado' => 'FALTA', 'minutos_tardanza' => 0, 'es_falta_turno' => true];
        }

        try {
            $horaFormato = Carbon::parse($hora)->format('Hi');
            $entradaTarde = Configuration::get('horario_entrada_tarde', '14:00');

            $horaInt = (int) $horaFormato;
            $limiteInt = (int) str_replace(':', '', $entradaTarde);

            if ($horaInt <= $limiteInt) {
                return ['estado' => 'PUNTUAL', 'minutos_tardanza' => 0, 'es_falta_turno' => false];
            } else {
                return ['estado' => 'FALTA', 'minutos_tardanza' => 0, 'es_falta_turno' => true];
            }
        } catch (\Exception $e) {
            return ['estado' => 'FALTA', 'minutos_tardanza' => 0, 'es_falta_turno' => true];
        }
    }

    protected function parsearFecha($fecha): ?string
    {
        if (empty($fecha)) {
            return null;
        }

        try {
            if (is_numeric($fecha)) {
                $carbon = Date::excelToDateTimeObject($fecha);

                return $carbon->format('Y-m-d');
            }

            $date = Carbon::parse($fecha);

            return $date->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getResultado(): array
    {
        return $this->resultado;
    }
}
