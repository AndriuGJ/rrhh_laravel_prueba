<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegimenRequest;
use App\Models\Employee;
use App\Models\Regimen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegimenController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->get('search');

        $query = Regimen::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        $regimens = $query->orderBy('nombre')->paginate(10);

        return view('regimens.index', compact('regimens', 'search'));
    }

    public function create(): View
    {
        return view('regimens.create');
    }

    public function store(RegimenRequest $request): RedirectResponse
    {
        Regimen::create($request->validated());

        return redirect()->route('regimens.index')
            ->with('success', 'Régimen creado correctamente.');
    }

    public function show(Regimen $regimen): View
    {
        $uso = Employee::where('regimen_laboral', $regimen->nombre)->count();

        return view('regimens.show', compact('regimen', 'uso'));
    }

    public function edit(Regimen $regimen): View
    {
        return view('regimens.edit', compact('regimen'));
    }

    public function update(RegimenRequest $request, Regimen $regimen): RedirectResponse
    {
        $regimen->update($request->validated());

        return redirect()->route('regimens.index')
            ->with('success', 'Régimen actualizado correctamente.');
    }

    public function destroy(Regimen $regimen): RedirectResponse
    {
        $uso = Employee::where('regimen_laboral', $regimen->nombre)->count();

        if ($uso > 0) {
            return redirect()->route('regimens.index')
                ->with('error', "No se puede eliminar: {$uso} empleado(s) lo tienen asignado. Desactívelo en su lugar.");
        }

        $regimen->delete();

        return redirect()->route('regimens.index')
            ->with('success', 'Régimen eliminado correctamente.');
    }
}
