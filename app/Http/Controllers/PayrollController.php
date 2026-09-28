<?php

namespace App\Http\Controllers;

use App\Exports\PayrollExcelExport;
use App\Http\Requests\PayrollRequest;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\Regimen;
use App\Services\PayrollService;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PayrollController extends Controller
{
    public function __construct(
        protected PayrollService $payrollService
    ) {}

    public function index(Request $request): View
    {
        $search = $request->get('search');
        $periodo = $request->get('periodo');
        $regimen = $request->get('regimen');
        $estado = $request->get('estado');

        $query = Payroll::with('employee');

        if ($search) {
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('nombres', 'like', "%{$search}%")
                    ->orWhere('apellidos', 'like', "%{$search}%")
                    ->orWhere('dni', 'like', "%{$search}%");
            });
        }

        if ($periodo) {
            $query->where('periodo', $periodo);
        }

        if ($regimen) {
            $query->whereHas('employee', function ($q) use ($regimen) {
                $q->where('regimen_laboral', $regimen);
            });
        }

        if ($estado) {
            $query->where('estado', $estado);
        }

        $payrolls = $query->orderBy('periodo', 'desc')->orderBy('employee_id')->paginate(6);

        $periodos = Payroll::distinct()->pluck('periodo')->filter();
        $regimenes = Regimen::where('estado', true)->orderBy('nombre')->pluck('nombre');

        return view('payrolls.index', compact('payrolls', 'search', 'periodo', 'regimen', 'estado', 'periodos', 'regimenes'));
    }

    public function create(Request $request): View
    {
        $search = $request->get('search');
        $regimen = $request->get('regimen');
        $periodo = $request->get('periodo') ?? Carbon::now()->format('Y-m');

        $query = Employee::where('activo', true);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombres', 'like', "%{$search}%")
                    ->orWhere('apellidos', 'like', "%{$search}%")
                    ->orWhere('dni', 'like', "%{$search}%");
            });
        }

        if ($regimen) {
            $query->where('regimen_laboral', $regimen);
        }

        $employees = $query->orderBy('apellidos')->get();
        $regimenes = Regimen::where('estado', true)->orderBy('nombre')->pluck('nombre');

        return view('payrolls.create', compact('employees', 'search', 'regimen', 'periodo', 'regimenes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $employeeIds = $request->input('employee_ids', []);
        $periodo = $request->input('periodo');

        if (empty($employeeIds)) {
            return back()->with('error', 'Seleccione al menos un empleado.');
        }

        if (! $periodo) {
            return back()->with('error', 'El período es obligatorio.');
        }

        $existing = Payroll::where('periodo', $periodo)
            ->whereIn('employee_id', $employeeIds)
            ->pluck('employee_id')
            ->toArray();

        $existingIds = array_intersect($employeeIds, $existing);
        if (! empty($existingIds)) {
            $existingEmployees = Employee::whereIn('id', $existingIds)->pluck('dni')->implode(', ');

            return back()->with('error', 'Ya existen planillas para los DNI: '.$existingEmployees.' en este período.');
        }

        $created = 0;
        foreach ($employeeIds as $employeeId) {
            $employee = Employee::find($employeeId);
            if ($employee && $employee->activo) {
                $this->payrollService->generarPlanilla($employee, $periodo);
                $created++;
            }
        }

        return redirect()->route('payrolls.index')
            ->with('success', "Se generaron {$created} planillas para el período {$periodo}.");
    }

    public function show(Payroll $payroll): View
    {
        $payroll->load('employee', 'incomes', 'discounts');

        return view('payrolls.show', compact('payroll'));
    }

    public function edit(Payroll $payroll): View
    {
        $employees = Employee::where('activo', true)->orderBy('apellidos')->get();

        return view('payrolls.edit', compact('payroll', 'employees'));
    }

    public function update(PayrollRequest $request, Payroll $payroll): RedirectResponse
    {
        $data = $request->validated();
        $payroll->update($data);

        return redirect()->route('payrolls.index')
            ->with('success', 'Planilla actualizada correctamente.');
    }

    public function destroy(Payroll $payroll): RedirectResponse
    {
        $payroll->delete();

        return redirect()->route('payrolls.index')
            ->with('success', 'Planilla eliminada correctamente.');
    }

    public function destroyPeriodo(Request $request): RedirectResponse
    {
        $request->validate([
            'periodo' => 'required',
        ]);

        $deleted = Payroll::where('periodo', $request->periodo)->delete();

        return redirect()->route('payrolls.index')
            ->with('success', "Se eliminaron {$deleted} planillas del período {$request->periodo}.");
    }

    public function marcarPagado(Payroll $payroll): RedirectResponse
    {
        $payroll->update([
            'estado' => 'PAGADA',
            'fecha_pago' => now(),
        ]);

        return back()->with('success', 'Planilla marcada como pagada.');
    }

    public function marcarPeriodoPagado(Request $request): RedirectResponse
    {
        $request->validate([
            'periodo' => 'required',
        ]);

        $query = Payroll::where('periodo', $request->periodo);

        if ($request->regimen && $request->regimen !== 'todos') {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('regimen_laboral', $request->regimen);
            });
        }

        $actualizados = $query->update([
            'estado' => 'PAGADA',
            'fecha_pago' => now(),
        ]);

        $regimenLabel = ($request->regimen && $request->regimen !== 'todos')
            ? $request->regimen
            : 'todos los regímenes';

        return back()->with('success', "{$actualizados} planillas del período {$request->periodo} ({$regimenLabel}) marcadas como pagadas.");
    }

    public function boleta(Payroll $payroll)
    {
        $payroll->load('employee', 'incomes', 'discounts');

        $options = new Options;
        $options->set('defaultFont', 'Helvetica');
        $options->set('chroot', [public_path(), storage_path('app/public')]);

        $dompdf = new Dompdf($options);
        $dompdf->setPaper('A4', 'portrait');

        $html = view('payrolls.boleta', compact('payroll'))->render();
        $dompdf->loadHtml($html);
        $dompdf->render();

        $filename = 'Boleta_Pago_'.$payroll->employee->dni.'_'.$payroll->periodo.'.pdf';

        return response()->make($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
        ]);
    }

    public function pdfGeneral(Request $request)
    {
        $periodo = $request->get('periodo');
        $regimen = $request->get('regimen');

        $regimen = $regimen ? urldecode($regimen) : null;

        if (! $periodo) {
            $periodo = Payroll::max('periodo');
        }

        if (! $periodo) {
            return back()->with('error', 'No hay planillas generadas.');
        }

        $query = Payroll::with('employee')->where('periodo', $periodo);

        if ($regimen && $regimen !== '' && $regimen !== 'Todos') {
            $query->whereHas('employee', function ($q) use ($regimen) {
                $q->where('regimen_laboral', 'LIKE', '%'.$regimen.'%');
            });
        }

        $payrolls = $query->orderBy('employee_id')->get();

        if ($payrolls->isEmpty()) {
            return back()->with('error', 'No hay planillas para el período: '.$periodo.' y régimen: '.($regimen ?? 'Todos'));
        }

        $regimenLabel = (! $regimen || $regimen === 'Todos') ? 'TODOS LOS REGÍMENES' : $regimen;

        $options = new Options;
        $options->set('defaultFont', 'Helvetica');
        $options->set('chroot', [public_path(), storage_path('app/public')]);

        $dompdf = new Dompdf($options);
        $dompdf->setPaper('A4', 'landscape');

        $html = view('payrolls.boleta-general', compact('payrolls', 'periodo', 'regimenLabel'))->render();
        $dompdf->loadHtml($html);
        $dompdf->render();

        $filename = 'Planilla_General_'.$periodo.'_'.($regimen ?? 'Todos').'.pdf';

        return response()->make($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
        ]);
    }

    public function excelGeneral(Request $request)
    {
        $periodo = $request->get('periodo');
        $regimen = $request->get('regimen');

        $regimen = $regimen ? urldecode($regimen) : null;

        if (! $periodo) {
            $periodo = Payroll::max('periodo');
        }

        if (! $periodo) {
            return back()->with('error', 'No hay planillas generadas.');
        }

        $query = Payroll::with('employee')->where('periodo', $periodo);

        if ($regimen && $regimen !== '' && $regimen !== 'Todos') {
            $query->whereHas('employee', function ($q) use ($regimen) {
                $q->where('regimen_laboral', 'LIKE', '%'.$regimen.'%');
            });
        }

        $payrolls = $query->orderBy('employee_id')->get();

        if ($payrolls->isEmpty()) {
            return back()->with('error', 'No hay planillas para el período y régimen seleccionados.');
        }

        $regimenLabel = (! $regimen || $regimen === 'Todos') ? 'TODOS LOS REGÍMENES' : $regimen;

        $exporter = new PayrollExcelExport($payrolls, $periodo, $regimenLabel);

        return $exporter->exportToResponse();
    }
}
