<?php

namespace App\Http\Controllers;

use App\Http\Requests\InductionRequest;
use App\Models\InductionConformity;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InductionController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $tipo = $request->get('tipo');
        $employeeId = $request->get('employee_id');

        $query = InductionConformity::with('employee');

        if ($search) {
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('nombres', 'like', "%{$search}%")
                  ->orWhere('apellidos', 'like', "%{$search}%");
            });
        }

        if ($tipo) {
            $query->where('tipo', $tipo);
        }

        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }

        $inductions = $query->orderByDesc('fecha')->paginate(10);

        return view('inductions.index', compact('inductions', 'search', 'tipo'));
    }

    public function create(): View
    {
        $employees = Employee::where('activo', true)->orderBy('apellidos')->get();
        return view('inductions.create', compact('employees'));
    }

    public function store(InductionRequest $request): RedirectResponse
    {
        InductionConformity::create($request->all());

        return redirect()->route('inductions.index')
            ->with('success', 'Conformidad de inducción creada correctamente.');
    }

    public function show(InductionConformity $induction): View
    {
        return view('inductions.show', compact('induction'));
    }

    public function edit(InductionConformity $induction): View
    {
        $employees = Employee::where('activo', true)->orderBy('apellidos')->get();
        return view('inductions.edit', compact('induction', 'employees'));
    }

    public function update(InductionRequest $request, InductionConformity $induction): RedirectResponse
    {
        $induction->update($request->all());

        return redirect()->route('inductions.index')
            ->with('success', 'Conformidad de inducción actualizada correctamente.');
    }

    public function destroy(InductionConformity $induction): RedirectResponse
    {
        $induction->delete();

        return redirect()->route('inductions.index')
            ->with('success', 'Conformidad de inducción eliminada correctamente.');
    }

    public function byEmployee($employeeId): RedirectResponse
    {
        return redirect()->route('inductions.index', ['employee_id' => $employeeId]);
    }
}