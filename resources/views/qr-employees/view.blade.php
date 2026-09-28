@extends('layouts.dashboard')

@section('title', 'Carnet QR')

@section('main-content')
    <div class="space-y-4 text-brandBlack">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('qr-employees.index') }}" class="p-2 hover:bg-stone/10">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <p class="text-[0.55rem] uppercase tracking-[0.6em] text-jetDark">QR - Empleados</p>
                    <h1 class="text-2xl font-semibold">Carnet de {{ $employee->nombres }} {{ $employee->apellidos }}</h1>
                </div>
            </div>
            <a href="{{ route('qr-employees.print', $employee->id) }}" class="inline-flex items-center px-4 py-2 bg-jet text-almondDark text-sm font-medium hover:bg-jetLight">
                <i class="fas fa-print mr-2"></i>Imprimir
            </a>
        </div>

        <!-- Carnet -->
        <div class="glass-card border border-white/40 p-8 flex flex-col items-center gap-4">
            <p class="text-xs uppercase tracking-[0.4em] text-stoneLight">Vista del carnet (5.5 cm × 8.5 cm)</p>
            @include('qr-employees.partials.carnet', ['qrDataUri' => $qrDataUri])
            <p class="text-xs text-stoneLight">Código: <code class="text-jet">{{ $employee->qr_code }}</code></p>
        </div>
    </div>
@endsection
