@extends('layouts.dashboard')

@section('title', 'Ver Planilla')

@section('main-content')
    <div class="space-y-4 text-brandBlack">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('payrolls.index') }}" class="p-2 hover:bg-stone/10">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <p class="text-[0.55rem] uppercase tracking-[0.6em] text-jetDark">RRHH</p>
                    <h1 class="text-2xl font-semibold">Planilla - {{ $payroll->periodo }}</h1>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('payrolls.boleta', $payroll->id) }}" target="_blank" class="px-4 py-2 bg-stone text-white text-sm font-medium hover:bg-stoneLight">
                    <i class="fas fa-file-pdf mr-2"></i>Boleta PDF
                </a>
                @if($payroll->estado === 'GENERADA')
                <form action="{{ route('payrolls.marcarPagado', $payroll->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white text-sm font-medium hover:bg-green-700" onclick="return confirm('¿Marcar como pagado?')">
                        <i class="fas fa-check mr-2"></i>Marcar Pagado
                    </button>
                </form>
                @endif
            </div>
        </div>

        <!-- Info -->
        <div class="glass-card border border-white/40 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-sm font-semibold text-stone border-b border-stone/20 pb-2 mb-3">Datos del Empleado</h3>
                    <div class="space-y-2 text-sm">
                        <p><span class="text-stoneLight">Nombre:</span> {{ $payroll->employee->nombres }} {{ $payroll->employee->apellidos }}</p>
                        <p><span class="text-stoneLight">DNI:</span> {{ $payroll->employee->dni }}</p>
                        <p><span class="text-stoneLight">Régimen:</span> {{ $payroll->employee->regimen_laboral }}</p>
                        <p><span class="text-stoneLight">Cargo:</span> {{ $payroll->employee->cargo }}</p>
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-stone border-b border-stone/20 pb-2 mb-3">Datos de Planilla</h3>
                    <div class="space-y-2 text-sm">
                        <p><span class="text-stoneLight">Período:</span> {{ $payroll->periodo }}</p>
                        <p><span class="text-stoneLight">Sueldo Básico:</span> S/. {{ number_format($payroll->sueldo_basico, 2) }}</p>
                        <p><span class="text-stoneLight">Asignación Familiar:</span> S/. {{ number_format($payroll->asignacion_familiar, 2) }}</p>
                        <p><span class="text-stoneLight">Estado:</span> 
                            @if($payroll->estado === 'PAGADA')
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs">Pagada</span>
                            @else
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs">Generada</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

<!-- Calculations -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-semibold text-stone border-b border-stone/20 pb-2 mb-3">Cálculo de Remuneración Bruta</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span>Sueldo Básico</span>
                            <span>S/. {{ number_format($payroll->sueldo_basico, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>+ Asignación Familiar</span>
                            <span>S/. {{ number_format($payroll->asignacion_familiar, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-green-600">
                            <span>+ Bonif. Remunerativas</span>
                            <span>S/. {{ number_format($payroll->bonificaciones_remunerativas ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>+ Horas Extras</span>
                            <span>S/. {{ number_format($payroll->monto_horas_extras ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-red-600">
                            <span>- Descuentos (Tard/Faltas)</span>
                            <span>-S/. {{ number_format(($payroll->monto_tardanzas + $payroll->monto_faltas), 2) }}</span>
                        </div>
                        <div class="flex justify-between font-bold border-t border-stone/20 pt-2 bg-stone/10 px-2 -mx-2">
                            <span>= Remuneración Bruta</span>
                            <span class="font-bold">S/. {{ number_format($payroll->remuneracion_bruta, 2) }}</span>
                        </div>
                    </div>
                </div>
 
                <div>
                    <h3 class="text-sm font-semibold text-stone border-b border-stone/20 pb-2 mb-3">Descuentos</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span>{{ $payroll->sistema_pensiones }}
                                @if($payroll->sistema_pensiones === 'ONP')
                                    (13%)
                                @elseif($payroll->sistema_pensiones === 'NO_APORTE')
                                    (0%)
                                @else
                                    ({{ number_format($payroll->retencion_pensiones / $payroll->remuneracion_bruta * 100, 2) }}%)
                                @endif
                            </span>
                            <span class="text-red-600">-S/. {{ number_format($payroll->retencion_pensiones, 2) }}</span>
                        </div>
                        @if($payroll->renta_quinta > 0)
                        <div class="flex justify-between">
                            <span>- Renta Quinta Categoría</span>
                            <span class="text-red-600">-S/. {{ number_format($payroll->renta_quinta, 2) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between font-bold border-t border-stone/20 pt-2">
                            <span>= Total Descuentos</span>
                            <span class="text-red-600 font-bold">-S/. {{ number_format($payroll->total_descuentos, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen -->
            <div class="mt-6 p-4 bg-stone/10 border border-stone/20 rounded">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                    <div>
                        <div class="text-xs text-stoneLight">Total Ingresos</div>
                        <div class="text-lg font-bold">S/. {{ number_format($payroll->total_ingresos, 2) }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-stoneLight">Total Descuentos</div>
                        <div class="text-lg font-bold text-red-600">-S/. {{ number_format($payroll->total_descuentos, 2) }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-stoneLight">Neto a Pagar</div>
                        <div class="text-xl font-bold text-green-600">S/. {{ number_format($payroll->neto_pagar, 2) }}</div>
                    </div>
                </div>
            </div>

            <!-- Aportes del Empleador -->
            <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded">
                <h3 class="text-sm font-semibold text-amber-800 mb-3">Aportes del Empleador</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-stone">ESSALUD (9%)</span>
                        <span class="font-medium">S/. {{ number_format($payroll->essalud, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-stone">SCTR - Salud</span>
                        <span class="font-medium">S/. {{ number_format($payroll->sctr_salud, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-stone">SCTR - Pensiones</span>
                        <span class="font-medium">S/. {{ number_format($payroll->sctr_pensiones, 2) }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-amber-900 border-t border-amber-300 pt-2">
                        <span>Total Aportes</span>
                        <span>S/. {{ number_format($payroll->total_aportes_empleador, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Neto -->
            <div class="mt-6 p-4 bg-jet text-almondDark text-center">
                <div class="text-lg font-bold">NETO A PAGAR</div>
                <div class="text-3xl font-bold">S/. {{ number_format($payroll->neto_pagar, 2) }}</div>
            </div>
        </div>
    </div>
@endsection