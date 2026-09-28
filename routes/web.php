<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BonusTypeController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DependentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InductionController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\QrEmployeeController;
use App\Http\Controllers\RegimenController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
// Empleados (Resource)
Route::resource('employees', EmployeeController::class)->middleware('auth');
// Derechohabientes (Resource)
Route::resource('dependents', DependentController::class)->middleware('auth');
// Inducciones (Resource)
Route::resource('inductions', InductionController::class)->middleware('auth');

// Rutas adicionales para ver por empleado
Route::get('/employees/{employee}/dependents', [DependentController::class, 'byEmployee'])->name('dependents.byEmployee');
Route::get('/employees/{employee}/inductions', [InductionController::class, 'byEmployee'])->name('inductions.byEmployee');

// QR - Empleados
Route::get('/qr-employees', [QrEmployeeController::class, 'index'])->name('qr-employees.index')->middleware('auth');
Route::get('/qr-employees/{employee}/card', [QrEmployeeController::class, 'card'])->name('qr-employees.card')->middleware('auth');
Route::get('/qr-employees/{employee}/print', [QrEmployeeController::class, 'print'])->name('qr-employees.print')->middleware('auth');
Route::get('/qr-employees/{employee}', [QrEmployeeController::class, 'view'])->name('qr-employees.view')->middleware('auth');

// Planillas - rutas específicas primero
Route::get('/payrolls/pdf-general', [PayrollController::class, 'pdfGeneral'])->name('payrolls.pdfGeneral')->middleware('auth');
Route::get('/payrolls/excel-general', [PayrollController::class, 'excelGeneral'])->name('payrolls.excelGeneral')->middleware('auth');
Route::post('/payrolls/destroy-periodo', [PayrollController::class, 'destroyPeriodo'])->name('payrolls.destroyPeriodo')->middleware('auth');
Route::post('/payrolls/marcar-periodo-pagado', [PayrollController::class, 'marcarPeriodoPagado'])->name('payrolls.marcarPeriodoPagado')->middleware('auth');
Route::resource('payrolls', PayrollController::class)->middleware('auth');
Route::post('/payrolls/{payroll}/marcar-pagado', [PayrollController::class, 'marcarPagado'])->name('payrolls.marcarPagado')->middleware('auth');
Route::get('/payrolls/{payroll}/boleta', [PayrollController::class, 'boleta'])->name('payrolls.boleta')->middleware('auth');

// Asistencia
Route::get('/attendances/search', [AttendanceController::class, 'searchEmployee'])->name('attendances.search')->middleware('auth');
Route::post('/attendances/destroy-range', [AttendanceController::class, 'destroyRange'])->name('attendances.destroyRange')->middleware('auth');
Route::get('/attendances/import', [AttendanceController::class, 'import'])->name('attendances.import')->middleware('auth');
Route::post('/attendances/importar', [AttendanceController::class, 'importarExcel'])->name('attendances.importar')->middleware('auth');
Route::get('/attendances/plantilla', [AttendanceController::class, 'plantilla'])->name('attendances.plantilla')->middleware('auth');
Route::resource('attendances', AttendanceController::class)->middleware('auth');

// Configuración (solo administradores)
Route::prefix('configurations')->name('configurations.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [ConfigurationController::class, 'index'])->name('index');
    Route::put('/', [ConfigurationController::class, 'update'])->name('update');
});

// Bonificaciones (solo administradores)
Route::resource('bonifications', BonusTypeController::class)->middleware(['auth', 'admin']);

// Regímenes (solo administradores)
Route::resource('regimens', RegimenController::class)->middleware(['auth', 'admin']);

// Usuarios (solo administradores)
Route::resource('users', UserController::class)->middleware(['auth', 'admin']);
