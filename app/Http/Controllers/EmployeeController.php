<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Models\BonusType;
use App\Models\Employee;
use App\Models\EmployeeBonus;
use App\Models\Regimen;
use App\Services\DocumentService;
use App\Support\QrGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function __construct(
        protected DocumentService $documentService
    ) {}

    public function index(Request $request): View
    {
        $search = $request->get('search');
        $estado = $request->get('estado');

        $query = Employee::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombres', 'like', "%{$search}%")
                    ->orWhere('apellidos', 'like', "%{$search}%")
                    ->orWhere('dni', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($estado !== null && $estado !== '') {
            $query->where('estado', $estado);
        }

        $employees = $query->orderBy('apellidos')->paginate(10);

        return view('employees.index', compact('employees', 'search', 'estado'));
    }

    public function create(): View
    {
        $bonusTypes = BonusType::where('estado', true)->orderBy('nombre')->get();
        $regimenes = Regimen::where('estado', true)->orderBy('nombre')->get();

        return view('employees.create', compact('bonusTypes', 'regimenes'));
    }

    public function store(EmployeeRequest $request): RedirectResponse
    {
        $data = $request->except(['documento_archivo', 'qr_code']);

        $data['tiene_asignacion_familiar'] = $request->has('tiene_asignacion_familiar');
        $data['conformidad_tregistro'] = $request->has('conformidad_tregistro');
        $data['activo'] = $request->has('activo') ?? true;
        $data['afp_tiene_jubilacion'] = $request->has('afp_tiene_jubilacion');
        $data['aplica_renta_quinta'] = $request->has('aplica_renta_quinta');
        $data['qr_code'] = QrGenerator::generarCodigo();

        $employee = Employee::create($data);

        if ($request->hasFile('documento_archivo') && ! empty($data['dni'])) {
            $archivo = $request->file('documento_archivo');
            $extension = strtolower($archivo->getClientOriginalExtension());
            $nombreArchivo = $data['dni'].'_ingreso_'.time().'.'.$extension;
            $carpeta = 'documents/employees';

            if (! Storage::disk('public')->exists($carpeta)) {
                Storage::disk('public')->makeDirectory($carpeta);
            }

            $path = $archivo->storeAs($carpeta, $nombreArchivo, 'public');

            if ($path) {
                $employee->update(['documento_path' => $path]);
            }
        }

        $bonuses = $request->input('bonuses', []);
        foreach ($bonuses as $bonus) {
            if (! empty($bonus['bonus_type_id']) && ! empty($bonus['monto'])) {
                EmployeeBonus::create([
                    'employee_id' => $employee->id,
                    'bonus_type_id' => $bonus['bonus_type_id'],
                    'monto' => $bonus['monto'],
                    'estado' => true,
                ]);
            }
        }

        return redirect()->route('employees.index')
            ->with('success', 'Empleado creado correctamente.');
    }

    public function show(Employee $employee): View
    {
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee): View
    {
        $bonusTypes = BonusType::where('estado', true)->orderBy('nombre')->get();
        $employeeBonusesMap = $employee->employeeBonuses()->pluck('monto', 'bonus_type_id')->toArray();
        $regimenes = Regimen::where('estado', true)->orderBy('nombre')->get();

        return view('employees.edit', compact('employee', 'bonusTypes', 'employeeBonusesMap', 'regimenes'));
    }

    public function update(EmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $fields = [
            'nombres', 'apellidos', 'dni', 'email', 'telefono', 'fecha_nacimiento',
            'direccion', 'cargo', 'nivel', 'sueldo_basico', 'fecha_ingreso', 'fecha_cese',
            'regimen_laboral', 'regimen_pensionario', 'cuspp', 'banco', 'cuenta_bancaria',
            'numero_cts', 'meta', 'rubro', 'documento_ingreso',
            'asignacion_familiar_porcentaje', 'asignacion_familiar_monto', 'bonificacion',
            'afp_especifica', 'afp_tipo_comision', 'renta_quinta_porcentaje', 'renta_quinta_uit',
        ];

        $data = $request->only($fields);
        $data = array_map(function ($value) {
            return $value === '' ? null : $value;
        }, $data);

        $data['tiene_asignacion_familiar'] = $request->has('tiene_asignacion_familiar');
        $data['conformidad_tregistro'] = $request->has('conformidad_tregistro');
        $data['activo'] = $request->has('activo') ?? true;
        $data['afp_tiene_jubilacion'] = $request->has('afp_tiene_jubilacion');
        $data['aplica_renta_quinta'] = $request->has('aplica_renta_quinta');

        if ($employee->documento_path) {
            Storage::disk('public')->delete($employee->documento_path);
        }

        if ($request->hasFile('documento_archivo')) {
            $archivo = $request->file('documento_archivo');
            $extension = strtolower($archivo->getClientOriginalExtension());
            $nombreArchivo = $employee->dni.'_ingreso_'.time().'.'.$extension;
            $carpeta = 'documents/employees';

            if (! Storage::disk('public')->exists($carpeta)) {
                Storage::disk('public')->makeDirectory($carpeta);
            }

            $path = $archivo->storeAs($carpeta, $nombreArchivo, 'public');

            if ($path) {
                $data['documento_path'] = $path;
            }
        } elseif ($request->has('eliminar_documento')) {
            $data['documento_path'] = null;
        }

        $employee->update($data);

        $employee->employeeBonuses()->delete();

        $bonuses = $request->input('bonuses', []);
        foreach ($bonuses as $bonus) {
            if (! empty($bonus['bonus_type_id']) && isset($bonus['selected'])) {
                EmployeeBonus::create([
                    'employee_id' => $employee->id,
                    'bonus_type_id' => $bonus['bonus_type_id'],
                    'monto' => $bonus['monto'] ?? 0,
                    'estado' => true,
                ]);
            }
        }

        return redirect()->route('employees.index')
            ->with('success', 'Empleado actualizado correctamente.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        if ($employee->documento_path) {
            Storage::disk('public')->delete($employee->documento_path);
        }
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Empleado eliminado correctamente.');
    }
}
