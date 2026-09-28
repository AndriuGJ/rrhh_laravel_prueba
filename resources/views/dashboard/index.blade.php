@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('main-content')
    <div class="space-y-10 text-brandBlack">
        <!-- Header -->
        <div class="relative overflow-hidden p-6 bg-gradient-to-br from-brandBlack via-jet to-stone text-almondDark rounded-lg">
            <div class="absolute inset-0 opacity-40" style="background-image: radial-gradient(circle at 10% 10%, rgba(198,172,143,.4), transparent 50%), radial-gradient(circle at 90% 0%, rgba(148,137,125,.35), transparent 45%);"></div>
            <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <p class="text-[0.55rem] uppercase tracking-[0.6em] text-khakiDark">Panel principal</p>
                    <h1 class="text-3xl font-semibold leading-tight mt-2">Resumen del Sistema</h1>
                    <p class="text-sm text-almond/80">Resumen general de todos los módulos del sistema.</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="glass-card p-4 border border-white/30">
                <p class="text-[0.55rem] uppercase tracking-[0.5em] text-jetDark">Total Empleados</p>
                <p class="text-3xl font-semibold mt-2">{{ $totalEmployees }}</p>
                <span class="text-xs text-stoneLight">Personal registrado</span>
            </div>
            <div class="glass-card p-4 border border-white/30">
                <p class="text-[0.55rem] uppercase tracking-[0.5em] text-jetDark">Activos</p>
                <p class="text-3xl font-semibold mt-2">{{ $activeEmployees }}</p>
                <span class="text-xs text-stoneLight">En actividad</span>
            </div>
            <div class="glass-card p-4 border border-white/30">
                <p class="text-[0.55rem] uppercase tracking-[0.5em] text-jetDark">Inactivos</p>
                <p class="text-3xl font-semibold mt-2">{{ $inactiveEmployees }}</p>
                <span class="text-xs text-stoneLight">Cesados o inactivos</span>
            </div>
            <div class="glass-card p-4 border border-white/30">
                <p class="text-[0.55rem] uppercase tracking-[0.5em] text-jetDark">Planillas</p>
                <p class="text-3xl font-semibold mt-2">{{ $totalPayrolls }}</p>
                <span class="text-xs text-stoneLight">Generadas este mes</span>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="glass-card border border-white/40 p-6">
            <p class="text-[0.55rem] uppercase tracking-[0.5em] text-jet mb-4">Acciones Rápidas</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('employees.create') }}" class="flex items-center gap-3 p-3 border border-stone/30 hover:bg-khaki/10 transition">
                    <i class="fas fa-user-plus text-jet"></i>
                    <span class="text-sm">Nuevo Empleado</span>
                </a>
                <a href="{{ route('payrolls.create') }}" class="flex items-center gap-3 p-3 border border-stone/30 hover:bg-khaki/10 transition">
                    <i class="fas fa-file-invoice-dollar text-jet"></i>
                    <span class="text-sm">Generar Planilla</span>
                </a>
                <a href="{{ route('attendances.index') }}" class="flex items-center gap-3 p-3 border border-stone/30 hover:bg-khaki/10 transition">
                    <i class="fas fa-calendar-check text-jet"></i>
                    <span class="text-sm">Registrar Asistencia</span>
                </a>
                <a href="{{ route('configurations.index') }}" class="flex items-center gap-3 p-3 border border-stone/30 hover:bg-khaki/10 transition">
                    <i class="fas fa-cog text-jet"></i>
                    <span class="text-sm">Configuración</span>
                </a>
            </div>
        </div>
    </div>
@endsection