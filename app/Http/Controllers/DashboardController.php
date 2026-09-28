<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Payroll;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('activo', true)->count();
        $inactiveEmployees = Employee::where('activo', false)->count();
        $totalPayrolls = Payroll::count();

        return view('dashboard.index', compact(
            'totalEmployees',
            'activeEmployees',
            'inactiveEmployees',
            'totalPayrolls'
        ));
    }
}