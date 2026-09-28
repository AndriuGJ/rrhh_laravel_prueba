@extends('layouts.dashboard')

@section('title', 'Imprimir Carnet QR')

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
                    <h1 class="text-2xl font-semibold">Imprimir carnet</h1>
                </div>
            </div>
            <a href="{{ route('qr-employees.card', $employee->id) }}" target="_blank" rel="noopener" class="inline-flex items-center px-4 py-2 bg-jet text-almondDark text-sm font-medium hover:bg-jetLight">
                <i class="fas fa-external-link-alt mr-2"></i>Abrir carnet para imprimir
            </a>
        </div>

        <!-- Vista preliminar -->
        <div class="glass-card border border-white/40 p-8 flex flex-col items-center gap-4">
            <p class="text-xs uppercase tracking-[0.4em] text-stoneLight">Vista preliminar</p>
            @include('qr-employees.partials.carnet', ['qrDataUri' => $qrDataUri])
        </div>

        <!-- Instrucciones -->
        <div class="glass-card border border-white/40 p-6">
            <h3 class="text-sm font-semibold text-stone border-b border-stone/20 pb-2 mb-3">
                <i class="fas fa-info-circle mr-2"></i>Instrucciones de impresión
            </h3>
            <ul class="text-sm text-stoneLight space-y-1 list-disc list-inside">
                <li>Tamaño del carnet: <strong class="text-stone">5.5 cm de ancho × 8.5 cm de alto</strong>.</li>
                <li>Al pulsar "Abrir carnet para imprimir" se abrirá el carnet en una <strong class="text-stone">pestaña nueva</strong>.</li>
                <li>En la ventana de impresión use <strong class="text-stone">escala 100%</strong> (sin "ajustar a la página") para conservar las medidas exactas.</li>
                <li>Si su impresora no tiene tamaño de tarjeta, el sistema configurará la página en <strong class="text-stone">5.5 cm × 8.5 cm</strong> automáticamente.</li>
            </ul>
            <div class="mt-4">
                <a href="{{ route('qr-employees.card', $employee->id) }}" target="_blank" rel="noopener" class="inline-flex items-center px-4 py-2 bg-stone text-white text-sm font-medium hover:bg-stoneLight">
                    <i class="fas fa-print mr-2"></i>Imprimir ahora
                </a>
            </div>
        </div>
    </div>
@endsection
