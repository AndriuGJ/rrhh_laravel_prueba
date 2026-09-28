<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceRequest;
use App\Models\Attendance;
use App\Models\Employee;
use App\Services\AttendanceImportService;
use App\Services\AttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class AttendanceController extends Controller
{
    public function __construct(
        protected AttendanceService $attendanceService,
        protected AttendanceImportService $importService
    ) {}

    public function index(Request $request): View
    {
        $search = $request->get('search');
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');

        $query = Attendance::with('employee');

        if ($search) {
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('nombres', 'like', "%{$search}%")
                    ->orWhere('apellidos', 'like', "%{$search}%")
                    ->orWhere('dni', 'like', "%{$search}%");
            });
        }

        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
        } elseif ($fechaInicio) {
            $query->where('fecha', '>=', $fechaInicio);
        } elseif ($fechaFin) {
            $query->where('fecha', '<=', $fechaFin);
        }

        $attendances = $query->orderBy('fecha', 'desc')->orderBy('employee_id')->paginate(20);

        return view('attendances.index', compact('attendances', 'search', 'fechaInicio', 'fechaFin'));
    }

    public function create(): View
    {
        $employees = Employee::where('activo', true)->orderBy('apellidos')->get();

        return view('attendances.create', compact('employees'));
    }

    public function store(AttendanceRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (!empty($data['tipo_licencia'])) {
            $fechaFin = !empty($data['fecha_fin_licencia'])
                ? $data['fecha_fin_licencia']
                : $data['fecha'];

            $count = 0;
            $fechaActual = \Carbon\Carbon::parse($data['fecha']);
            $fechaLimite = \Carbon\Carbon::parse($fechaFin);

            while ($fechaActual->lte($fechaLimite)) {
                if ($fechaActual->dayOfWeek !== 0 && $fechaActual->dayOfWeek !== 6) {
                    Attendance::create([
                        'employee_id' => $data['employee_id'],
                        'fecha' => $fechaActual->format('Y-m-d'),
                        'estado' => 'PUNTUAL',
                        'tipo_licencia' => $data['tipo_licencia'],
                        'fecha_fin_licencia' => $fechaLimite->format('Y-m-d'),
                        'observacion' => $data['observacion'] ?? null,
                        'turno' => 'COMPLETO',
                        'minutos_tardanza' => 0,
                        'es_falta_turno' => false,
                        'estado_registro' => true,
                    ]);
                    $count++;
                }
                $fechaActual->addDay();
            }

            return redirect()->route('attendances.index')
                ->with('success', "Licencia registrada: {$count} día(s) como puntual.");
        }

        $employee = Employee::find($data['employee_id']);

        if (!empty($data['hora_entrada'])) {
            $resultado = $this->attendanceService->determinarEstado($data['hora_entrada'], $data['turno']);
            $data['estado'] = $resultado['estado'];
            $data['minutos_tardanza'] = $resultado['minutos_tardanza'];
            $data['es_falta_turno'] = $resultado['es_falta_turno'] ?? false;
        } else {
            $data['estado'] = 'FALTA';
            $data['minutos_tardanza'] = 0;
            $data['es_falta_turno'] = true;
        }

        Attendance::create($data);

        return redirect()->route('attendances.index')
            ->with('success', 'Asistencia registrada correctamente.');
    }

    public function show(Attendance $attendance): View
    {
        return view('attendances.show', compact('attendance'));
    }

    public function edit(Attendance $attendance): View
    {
        $employees = Employee::where('activo', true)->orderBy('apellidos')->get();

        return view('attendances.edit', compact('attendance', 'employees'));
    }

    public function update(AttendanceRequest $request, Attendance $attendance): RedirectResponse
    {
        $data = $request->validated();

        if (!empty($data['tipo_licencia'])) {
            $data['estado'] = 'PUNTUAL';
            $data['minutos_tardanza'] = 0;
            $data['es_falta_turno'] = false;
            $data['turno'] = 'COMPLETO';
            $data['hora_entrada'] = null;
            $data['hora_salida'] = null;
        } elseif (!empty($data['hora_entrada'])) {
            $resultado = $this->attendanceService->determinarEstado($data['hora_entrada'], $data['turno']);
            $data['estado'] = $resultado['estado'];
            $data['minutos_tardanza'] = $resultado['minutos_tardanza'];
            $data['es_falta_turno'] = $resultado['es_falta_turno'] ?? false;
        } else {
            $data['estado'] = 'FALTA';
            $data['minutos_tardanza'] = 0;
            $data['es_falta_turno'] = true;
        }

        $attendance->update($data);

        return redirect()->route('attendances.index')
            ->with('success', 'Asistencia actualizada correctamente.');
    }

    public function destroy(Attendance $attendance): RedirectResponse
    {
        $attendance->delete();

        return redirect()->route('attendances.index')
            ->with('success', 'Asistencia eliminada correctamente.');
    }

    public function destroyRange(Request $request): RedirectResponse
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $deleted = Attendance::whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin])->delete();

        return redirect()->route('attendances.index')
            ->with('success', "Se eliminaron {$deleted} registros de asistencia.");
    }

    public function searchEmployee(Request $request)
    {
        $search = $request->get('q');

        $employees = Employee::where('activo', true)
            ->where(function ($q) use ($search) {
                $q->where('nombres', 'like', "%{$search}%")
                    ->orWhere('apellidos', 'like', "%{$search}%")
                    ->orWhere('dni', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get(['id', 'nombres', 'apellidos', 'dni']);

        return response()->json($employees);
    }

    public function import(): View
    {
        return view('attendances.import');
    }

    public function importarExcel(Request $request): RedirectResponse
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls',
        ]);

        $archivo = $request->file('archivo');

        try {
            $resultado = $this->importService->importarExcel($archivo);

            $mensaje = "Importación completada: {$resultado['creados']} creados, {$resultado['actualizados']} actualizados";

            if (! empty($resultado['errores'])) {
                $mensaje .= '. Errores: '.count($resultado['errores']);

                return back()->with('warning', $mensaje)->with('errores', $resultado['errores']);
            }

            return back()->with('success', $mensaje);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al importar: '.$e->getMessage());
        }
    }

    public function plantilla()
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        $cabeceras = ['ID', 'APELLIDO Y NOMBRES', 'FECHA_M1', 'ENTRADA_M1', 'FECHA_M2', 'SALIDA_M2', 'FECHA_T1', 'ENTRADA_T1', 'FECHA_T2', 'SALIDA_T2'];
        $sheet->fromArray($cabeceras, null, 'A1');

        $datos_ejemplo = [
            [1, 'GARCIA LOPEZ JUAN', '2026-04-01', '08:00', '2026-04-01', '13:00', '2026-04-01', '14:00', '2026-04-01', '18:00'],
            [2, 'PEREZ DIAZ MARIA', '2026-04-01', '08:10', '2026-04-01', '13:00', '2026-04-01', '', '', ''],
            [3, 'LOPEZ CASTRO PEDRO', '2026-04-01', '', '', '', '', '2026-04-01', '14:00', '2026-04-01', '18:00'],
        ];
        $sheet->fromArray($datos_ejemplo, null, 'A2');

        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Ejemplo_asistenciaExcel.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
