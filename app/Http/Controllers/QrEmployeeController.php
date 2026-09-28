<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Support\QrGenerator;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QrEmployeeController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->get('search');

        $query = Employee::query()->orderBy('apellidos')->orderBy('nombres');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombres', 'like', "%{$search}%")
                    ->orWhere('apellidos', 'like', "%{$search}%")
                    ->orWhere('dni', 'like', "%{$search}%")
                    ->orWhere('cargo', 'like', "%{$search}%");
            });
        }

        $employees = $query->paginate(10);

        return view('qr-employees.index', compact('employees', 'search'));
    }

    public function view(Employee $employee): View
    {
        return view('qr-employees.view', [
            'employee' => $employee,
            'qrDataUri' => QrGenerator::pngDataUri($employee->qr_code, 320),
        ]);
    }

    public function print(Employee $employee): View
    {
        return view('qr-employees.print', [
            'employee' => $employee,
            'qrDataUri' => QrGenerator::pngDataUri($employee->qr_code, 320),
        ]);
    }

    public function card(Employee $employee): View
    {
        return view('qr-employees.card', [
            'employee' => $employee,
            'qrDataUri' => QrGenerator::pngDataUri($employee->qr_code, 400),
        ]);
    }
}
