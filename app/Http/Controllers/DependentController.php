<?php

namespace App\Http\Controllers;

use App\Http\Requests\DependentRequest;
use App\Models\Dependent;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DependentController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $employeeId = $request->get('employee_id');

        $query = Dependent::with('employee');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombres', 'like', "%{$search}%")
                  ->orWhere('apellidos', 'like', "%{$search}%")
                  ->orWhere('dni', 'like', "%{$search}%");
            });
        }

        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }

        $dependents = $query->orderBy('apellidos')->paginate(10);

        return view('dependents.index', compact('dependents', 'search'));
    }

    public function create(): View
    {
        $employees = Employee::where('activo', true)->orderBy('apellidos')->get();
        return view('dependents.create', compact('employees'));
    }

    public function store(DependentRequest $request): RedirectResponse
    {
        Dependent::create($request->all());

        return redirect()->route('dependents.index')
            ->with('success', 'Derechohabiente creado correctamente.');
    }

    public function show(Dependent $dependent): View
    {
        return view('dependents.show', compact('dependent'));
    }

    public function edit(Dependent $dependent): View
    {
        $employees = Employee::where('activo', true)->orderBy('apellidos')->get();
        return view('dependents.edit', compact('dependent', 'employees'));
    }

    public function update(DependentRequest $request, Dependent $dependent): RedirectResponse
    {
        $dependent->update($request->all());

        return redirect()->route('dependents.index')
            ->with('success', 'Derechohabiente actualizado correctamente.');
    }

    public function destroy(Dependent $dependent): RedirectResponse
    {
        $dependent->delete();

        return redirect()->route('dependents.index')
            ->with('success', 'Derechohabiente eliminado correctamente.');
    }

    public function byEmployee($employeeId): RedirectResponse
    {
        return redirect()->route('dependents.index', ['employee_id' => $employeeId]);
    }
}