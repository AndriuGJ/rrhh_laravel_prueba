<?php

namespace App\Http\Controllers;

use App\Http\Requests\BonusTypeRequest;
use App\Models\BonusType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BonusTypeController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->get('search');

        $query = BonusType::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        $bonusTypes = $query->orderBy('nombre')->paginate(10);

        return view('bonifications.index', compact('bonusTypes', 'search'));
    }

    public function create(): View
    {
        return view('bonifications.create');
    }

    public function store(BonusTypeRequest $request): RedirectResponse
    {
        BonusType::create($request->validated());

        return redirect()->route('bonifications.index')
            ->with('success', 'Bonificación creada correctamente.');
    }

    public function show(BonusType $bonification): View
    {
        $bonification->load('employeeBonuses.employee');

        return view('bonifications.show', compact('bonification'));
    }

    public function edit(BonusType $bonification): View
    {
        return view('bonifications.edit', compact('bonification'));
    }

    public function update(BonusTypeRequest $request, BonusType $bonification): RedirectResponse
    {
        $bonification->update($request->validated());

        return redirect()->route('bonifications.index')
            ->with('success', 'Bonificación actualizada correctamente.');
    }

    public function destroy(BonusType $bonification): RedirectResponse
    {
        $uso = $bonification->employeeBonuses()->count();

        if ($uso > 0) {
            return redirect()->route('bonifications.index')
                ->with('error', "No se puede eliminar: está asignada a {$uso} empleado(s). Desactívela en su lugar.");
        }

        $bonification->delete();

        return redirect()->route('bonifications.index')
            ->with('success', 'Bonificación eliminada correctamente.');
    }
}
