<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QrAttendanceApiController extends Controller
{
    private const TZ = 'America/Lima';

    private const TIPOS = [
        'ENTRADA_MANANA' => ['turno' => 'MANANA', 'es_entrada' => true],
        'SALIDA_MANANA' => ['turno' => 'MANANA', 'es_entrada' => false],
        'ENTRADA_TARDE' => ['turno' => 'TARDE', 'es_entrada' => true],
        'SALIDA_TARDE' => ['turno' => 'TARDE', 'es_entrada' => false],
        'ENTRADA_COMPLETO' => ['turno' => 'COMPLETO', 'es_entrada' => true],
        'SALIDA_COMPLETO' => ['turno' => 'COMPLETO', 'es_entrada' => false],
    ];

    public function __construct(
        protected AttendanceService $attendanceService
    ) {}

    public function lookup(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:64',
        ]);

        [$empleado, $error] = $this->resolverEmpleado($validated['codigo']);

        if ($error !== null) {
            return $error;
        }

        return response()->json([
            'ok' => true,
            'empleado' => [
                'id' => $empleado->id,
                'nombres' => $empleado->nombres,
                'apellidos' => $empleado->apellidos,
                'dni' => $empleado->dni,
                'cargo' => $empleado->cargo,
                'regimen_laboral' => $empleado->regimen_laboral,
                'activo' => (bool) $empleado->activo,
            ],
        ]);
    }

    public function mark(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:64',
            'tipo_marcacion' => 'required|in:'.implode(',', array_keys(self::TIPOS)),
            'fecha' => 'required|date',
            'hora' => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
        ]);

        [$empleado, $error] = $this->resolverEmpleado($validated['codigo']);

        if ($error !== null) {
            return $error;
        }

        $fecha = $this->validarFecha($validated['fecha']);
        if ($fecha === null) {
            return $this->error('Fecha fuera de rango. Solo se admite desde hace 3 días hasta hoy', 'INVALID_DATE', 422);
        }

        $horaTexto = $this->validarHora($validated['hora'], $fecha);
        if ($horaTexto === null) {
            return $this->error('Hora desfasada de la hora del servidor. Revisa la hora del teléfono', 'INVALID_DATE', 422);
        }

        $tipo = self::TIPOS[$validated['tipo_marcacion']];
        $turno = $tipo['turno'];
        $esEntrada = $tipo['es_entrada'];
        $etiquetaTurno = match ($turno) {
            'MANANA' => 'mañana',
            'TARDE' => 'tarde',
            default => 'jornada completa',
        };

        $asistencia = Attendance::query()
            ->where('employee_id', $empleado->id)
            ->whereDate('fecha', $fecha->format('Y-m-d'))
            ->where('turno', $turno)
            ->first();

        if (! $asistencia) {
            $asistencia = new Attendance([
                'employee_id' => $empleado->id,
                'fecha' => $fecha->format('Y-m-d'),
                'turno' => $turno,
            ]);
        }

        if ($asistencia->exists && $asistencia->tipo_licencia) {
            return $this->error("Tiene licencia registrada el {$fecha->format('d/m/Y')}", 'ALREADY_MARKED', 409);
        }

        $resultado = null;

        if ($esEntrada) {
            if ($asistencia->hora_entrada) {
                return $this->error("Ya registró entrada de {$etiquetaTurno}", 'ALREADY_MARKED', 409);
            }

            $resultado = $this->attendanceService->determinarEstado($horaTexto, $turno);

            $asistencia->hora_entrada = $horaTexto;
            $asistencia->estado = $resultado['estado'];
            $asistencia->minutos_tardanza = $resultado['minutos_tardanza'];
            $asistencia->es_falta_turno = $resultado['es_falta_turno'] ?? false;
        } else {
            if (! $asistencia->exists || ! $asistencia->hora_entrada) {
                return $this->error("No tiene entrada registrada de {$etiquetaTurno}", 'MARK_OUT_OF_ORDER', 409);
            }

            if ($asistencia->hora_salida) {
                return $this->error("Ya registró salida de {$etiquetaTurno}", 'ALREADY_MARKED', 409);
            }

            $asistencia->hora_salida = $horaTexto;
        }

        $asistencia->origen = 'qr';
        $asistencia->estado_registro = true;
        $asistencia->save();

        $mensaje = ($esEntrada ? 'Entrada' : 'Salida').' registrada';

        if ($esEntrada && $resultado) {
            $mensaje .= ' - '.$resultado['estado'];
        }

        return response()->json([
            'ok' => true,
            'mensaje' => $mensaje,
            'asistencia' => [
                'fecha' => $asistencia->fecha instanceof Carbon
                    ? $asistencia->fecha->format('Y-m-d')
                    : $asistencia->fecha,
                'turno' => $asistencia->turno,
                'estado' => $asistencia->estado,
                'minutos_tardanza' => (int) $asistencia->minutos_tardanza,
                'hora_entrada' => $asistencia->hora_entrada
                    ? Carbon::parse($asistencia->hora_entrada)->format('H:i')
                    : null,
                'hora_salida' => $asistencia->hora_salida
                    ? Carbon::parse($asistencia->hora_salida)->format('H:i')
                    : null,
            ],
        ]);
    }

    private function resolverEmpleado(string $codigo): array
    {
        $empleado = Employee::where('qr_code', $codigo)->first();

        if (! $empleado) {
            return [null, $this->error('QR no registrado', 'QR_NOT_FOUND', 404)];
        }

        if (! $empleado->activo || ! $empleado->estado) {
            return [null, $this->error('Empleado inactivo', 'EMPLOYEE_INACTIVE', 404)];
        }

        return [$empleado, null];
    }

    private function validarFecha(string $fecha): ?Carbon
    {
        try {
            $parsed = Carbon::createFromFormat('Y-m-d', $fecha, self::TZ)->startOfDay();
        } catch (\Throwable) {
            return null;
        }

        if ($parsed->format('Y-m-d') !== $fecha) {
            return null;
        }

        $hoy = Carbon::today(self::TZ);

        if ($parsed->gt($hoy) || $parsed->lt($hoy->copy()->subDays(3))) {
            return null;
        }

        return $parsed;
    }

    private function validarHora(string $hora, Carbon $fecha): ?string
    {
        $completa = strlen($hora) === 5 ? $hora.':00' : $hora;

        try {
            $marcacion = Carbon::createFromFormat('Y-m-d H:i:s', $fecha->format('Y-m-d').' '.$completa, self::TZ);
        } catch (\Throwable) {
            return null;
        }

        if ($marcacion->format('H:i') !== substr($completa, 0, 5)) {
            return null;
        }

        if ($fecha->isSameDay(Carbon::now(self::TZ))) {
            $difMinutos = abs(Carbon::now(self::TZ)->diffInMinutes($marcacion));

            if ($difMinutos > 60) {
                return null;
            }
        }

        return $marcacion->format('H:i');
    }

    private function error(string $mensaje, string $codigo, int $status): JsonResponse
    {
        return response()->json([
            'ok' => false,
            'error' => $mensaje,
            'codigo' => $codigo,
        ], $status);
    }
}
