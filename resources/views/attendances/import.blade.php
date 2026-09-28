@extends('layouts.dashboard')

@section('title', 'Importar Asistencia')

@section('main-content')
    <div class="space-y-4 text-brandBlack">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div>
                    <p class="text-[0.55rem] uppercase tracking-[0.6em] text-jetDark">RRHH</p>
                    <h1 class="text-2xl font-semibold">Importar Asistencia desde Excel</h1>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('attendances.plantilla') }}" class="px-4 py-2 bg-green-600 text-white text-sm font-medium hover:bg-green-700">
                    <i class="fas fa-download mr-2"></i>Descargar Excel de Ejemplo
                </a>
                <a href="{{ route('attendances.index') }}" class="px-4 py-2 border border-stone/30 text-stone text-sm font-medium hover:bg-stone/10">
                    <i class="fas fa-arrow-left mr-2"></i>Volver
                </a>
            </div>
        </div>

        <!-- Instrucciones -->
        <div class="glass-card border border-white/40 p-6">
            <h2 class="text-lg font-semibold mb-4">Consideraciones Importantes</h2>
            <div class="space-y-3">
                <div class="flex items-start gap-2">
                    <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                    <div>
                        <p class="font-medium">Formato del Excel</p>
                        <p class="text-sm text-stone">El archivo debe tener las siguientes columnas en este orden exacto:</p>
                    </div>
                </div>

                <div class="bg-stone/5 p-4 rounded-lg overflow-x-auto">
                    <table class="text-sm w-full">
                        <thead class="bg-stone/10">
                            <tr>
                                <th class="px-3 py-2 text-left">Columna</th>
                                <th class="px-3 py-2 text-left">Descripción</th>
                                <th class="px-3 py-2 text-left">Ejemplo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td class="px-3 py-2 font-mono">A</td><td class="px-3 py-2">ID del empleado</td><td class="px-3 py-2 text-stoneLight">1</td></tr>
                            <tr><td class="px-3 py-2 font-mono">B</td><td class="px-3 py-2">Apellido y nombres</td><td class="px-3 py-2 text-stoneLight">GARCIA LOPEZ JUAN</td></tr>
                            <tr><td class="px-3 py-2 font-mono">C</td><td class="px-3 py-2">Fecha entrada mañana (YYYY-MM-DD)</td><td class="px-3 py-2 text-stoneLight">2026-04-01</td></tr>
                            <tr><td class="px-3 py-2 font-mono">D</td><td class="px-3 py-2">Hora entrada mañana (HH:MM)</td><td class="px-3 py-2 text-stoneLight">08:00</td></tr>
                            <tr><td class="px-3 py-2 font-mono">E</td><td class="px-3 py-2">Fecha salida mañana (YYYY-MM-DD)</td><td class="px-3 py-2 text-stoneLight">2026-04-01</td></tr>
                            <tr><td class="px-3 py-2 font-mono">F</td><td class="px-3 py-2">Hora salida mañana (HH:MM)</td><td class="px-3 py-2 text-stoneLight">13:00</td></tr>
                            <tr><td class="px-3 py-2 font-mono">G</td><td class="px-3 py-2">Fecha entrada tarde (YYYY-MM-DD)</td><td class="px-3 py-2 text-stoneLight">2026-04-01</td></tr>
                            <tr><td class="px-3 py-2 font-mono">H</td><td class="px-3 py-2">Hora entrada tarde (HH:MM)</td><td class="px-3 py-2 text-stoneLight">14:00</td></tr>
                            <tr><td class="px-3 py-2 font-mono">I</td><td class="px-3 py-2">Fecha salida tarde (YYYY-MM-DD)</td><td class="px-3 py-2 text-stoneLight">2026-04-01</td></tr>
                            <tr><td class="px-3 py-2 font-mono">J</td><td class="px-3 py-2">Hora salida tarde (HH:MM)</td><td class="px-3 py-2 text-stoneLight">18:00</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex items-start gap-2 mt-4">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-1"></i>
                    <div>
                        <p class="font-medium">Reglas de cálculo</p>
                        <ul class="text-sm text-stone list-disc list-inside space-y-1">
                            <li><strong>Turno Mañana (8:00):</strong> Hasta las 8:00 = Puntual | 8:01 -> 8:05 = Tarde, se aplica descuento| Desde 8:06 = Tardanza</li>
                            <li><strong>Turno Tarde (14:00):</strong> Desde 14:01 = Falta (sin tolerancia)</li>
                            <li>Si no registra entrada = Falta</li>
                        </ul>
                    </div>
                </div>

                <div class="flex items-start gap-2 mt-4">
                    <i class="fas fa-check-circle text-green-600 mt-1"></i>
                    <div>
                        <p class="font-medium">Casos especiales</p>
                        <ul class="text-sm text-stone list-disc list-inside space-y-1">
                            <li>Si celda vacía = No registró asistencia/Entrada</li>
                            <li>La columna B (nombre) es muy importante , el sistema usa la columna para la comparacion</li>
                            <li>El sistema actualiza si ya existe registro para esa fecha+turno</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de importación -->
        <div class="glass-card border border-white/40 p-6">
            <h2 class="text-lg font-semibold mb-4">Subir Archivo Excel</h2>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                    <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('warning') }}
                    @if(session('errores'))
                        <div class="mt-2 text-sm">
                            <p class="font-medium">Errores encontrados:</p>
                            <ul class="list-disc list-inside">
                                @foreach(session('errores') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <i class="fas fa-times-circle mr-2"></i>{{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('attendances.importar') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-stone mb-2">Archivo Excel (.xlsx o .xls)</label>
                    <input type="file" name="archivo" accept=".xlsx,.xls" required
                        class="w-full px-4 py-3 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-jet text-almondDark font-medium hover:bg-jetLight">
                        <i class="fas fa-upload mr-2"></i>Importar
                    </button>
                </div>
            </form>
        </div>

        <!-- Ejemplo visual -->
        <div class="glass-card border border-white/40 p-6">
            <h2 class="text-lg font-semibold mb-4">Ejemplo de formato</h2>
            <div class="flex justify-center">
                <img src="{{ asset('img/Example_importExcel.png') }}" alt="Ejemplo formato Excel" class="max-w-full h-auto border border-stone/30 rounded-lg">
            </div>
        </div>
    </div>
@endsection
