@extends('layouts.dashboard')

@section('title', 'Ver Empleado')

@section('main-content')
    <div class="space-y-4 text-brandBlack">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('employees.index') }}" class="p-2 hover:bg-stone/10">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <p class="text-[0.55rem] uppercase tracking-[0.6em] text-jetDark">Gestión de personal</p>
                    <h1 class="text-2xl font-semibold">Detalles del Empleado</h1>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('employees.edit', $employee->id) }}" class="px-4 py-2 bg-stone text-white text-sm font-medium hover:bg-stoneLight">
                    <i class="fas fa-edit mr-2"></i>Editar
                </a>
                <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-medium hover:bg-red-700" onclick="return confirm('¿Está seguro de eliminar este empleado?')">
                        <i class="fas fa-trash mr-2"></i>Eliminar
                    </button>
                </form>
            </div>
        </div>

        <!-- Info Card -->
        <div class="glass-card border border-white/40 p-6">
            <div class="flex items-start gap-6 mb-6">
                <div class="h-20 w-20 rounded-full bg-khaki/20 flex items-center justify-center text-3xl font-semibold text-stone">
                    {{ substr($employee->nombres, 0, 1) }}{{ substr($employee->apellidos, 0, 1) }}
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-semibold">{{ $employee->nombres }} {{ $employee->apellidos }}</h2>
                    <p class="text-stoneLight">{{ $employee->cargo ?? 'Sin cargo asignado' }}</p>
                    <div class="flex gap-2 mt-2">
                        @if($employee->activo)
                        <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-medium">Activo</span>
                        @else
                        <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-800 text-xs font-medium">Inactivo</span>
                        @endif
                        <span class="inline-flex items-center px-2 py-1 bg-khaki/20 text-stone text-xs font-medium">
                            {{ $employee->regimen_laboral ? \Str::before($employee->regimen_laboral, '(') : 'Sin régimen' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Datos Personales -->
                <div class="space-y-3">
                    <h3 class="text-sm font-semibold text-stone border-b border-stone/20 pb-2">Datos Personales</h3>
                    <div class="text-sm space-y-2">
                        <p><span class="text-stoneLight">DNI:</span> {{ $employee->dni }}</p>
                        <p><span class="text-stoneLight">Email:</span> {{ $employee->email }}</p>
                        <p><span class="text-stoneLight">Teléfono:</span> {{ $employee->telefono ?? '-' }}</p>
                        <p><span class="text-stoneLight">F. Nacimiento:</span> {{ $employee->fecha_nacimiento ? \Carbon\Carbon::parse($employee->fecha_nacimiento)->format('d/m/Y') : '-' }}</p>
                        <p><span class="text-stoneLight">Dirección:</span> {{ $employee->direccion ?? '-' }}</p>
                    </div>
                </div>

                <!-- Datos Laborales -->
                <div class="space-y-3">
                    <h3 class="text-sm font-semibold text-stone border-b border-stone/20 pb-2">Datos Laborales</h3>
                    <div class="text-sm space-y-2">
                        <p><span class="text-stoneLight">Cargo:</span> {{ $employee->cargo ?? '-' }}</p>
                        <p><span class="text-stoneLight">Nivel:</span> {{ $employee->nivel ?? '-' }}</p>
                        <p><span class="text-stoneLight">Sueldo:</span> S/. {{ number_format($employee->sueldo_basico, 2) }}</p>
                        <p><span class="text-stoneLight">F. Ingreso:</span> {{ $employee->fecha_ingreso ? \Carbon\Carbon::parse($employee->fecha_ingreso)->format('d/m/Y') : '-' }}</p>
                        <p><span class="text-stoneLight">F. Cese:</span> {{ $employee->fecha_cese ? \Carbon\Carbon::parse($employee->fecha_cese)->format('d/m/Y') : '-' }}</p>
                        @if($employee->documento_ingreso)
                        <p><span class="text-stoneLight">Doc. Ingreso:</span> {{ $employee->documento_ingreso }}</p>
                        @endif
                        @if($employee->documento_path)
                        <div class="mt-2 pt-2 border-t border-stone/20">
                            <p class="text-stoneLight mb-2">Documento:</p>
                            @php
                                $extension = strtolower(pathinfo($employee->documento_path, PATHINFO_EXTENSION));
                                $icono = match($extension) {
                                    'pdf' => 'fa-file-pdf text-red-600',
                                    'docx' => 'fa-file-word text-blue-600',
                                    'png', 'jpg', 'jpeg' => 'fa-file-image text-green-600',
                                    default => 'fa-file text-stone'
                                };
                                $bgIcono = match($extension) {
                                    'pdf' => 'bg-red-100',
                                    'docx' => 'bg-blue-100',
                                    'png', 'jpg', 'jpeg' => 'bg-green-100',
                                    default => 'bg-stone/10'
                                };
                            @endphp
                            <div class="flex items-center gap-3">
                                <div class="{{ $bgIcono }} p-3 rounded-lg">
                                    <i class="fas {{ $icono }} text-2xl"></i>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <span class="text-xs font-medium text-stone uppercase">{{ $extension }}</span>
                                    <div class="flex gap-2">
                                        <a href="{{ asset('storage/' . $employee->documento_path) }}" target="_blank" class="text-xs text-jet hover:underline">
                                            <i class="fas fa-eye mr-1"></i>Abrir
                                        </a>
                                        <a href="{{ asset('storage/' . $employee->documento_path) }}" download="{{ $employee->dni }}_documento.{{ $extension }}" class="text-xs text-jet hover:underline">
                                            <i class="fas fa-download mr-1"></i>Descargar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Datos Pensionarios -->
                <div class="space-y-3">
                    <h3 class="text-sm font-semibold text-stone border-b border-stone/20 pb-2">Datos Pensionarios</h3>
                    <div class="text-sm space-y-2">
                        <p><span class="text-stoneLight">Régimen:</span> {{ $employee->regimen_pensionario }}</p>
                        <p><span class="text-stoneLight">AFP:</span> {{ $employee->afp_especifica ?? '-' }}</p>
                        <p><span class="text-stoneLight">Comisión:</span> {{ $employee->afp_tipo_comision ?? '-' }}</p>
                        <p><span class="text-stoneLight">CUSPP:</span> {{ $employee->cuspp ?? '-' }}</p>
                    </div>
                </div>

                <!-- Datos Bancarios -->
                <div class="space-y-3">
                    <h3 class="text-sm font-semibold text-stone border-b border-stone/20 pb-2">Datos Bancarios</h3>
                    <div class="text-sm space-y-2">
                        <p><span class="text-stoneLight">Banco:</span> {{ $employee->banco ?? '-' }}</p>
                        <p><span class="text-stoneLight">Cuenta:</span> {{ $employee->cuenta_bancaria ?? '-' }}</p>
                        <p><span class="text-stoneLight">CTS:</span> {{ $employee->numero_cts ?? '-' }}</p>
                        <p><span class="text-stoneLight">Meta:</span> {{ $employee->meta ?? '-' }}</p>
                    </div>
                </div>

            <!-- Bonificaciones -->
            @php
                $allBonuses = $employee->employeeBonuses->map(function($b) {
                    $b->tipo = $b->bonusType ? ($b->bonusType->es_remunerativo ? 'Remunerativa' : 'No Remunerativa') : '-';
                    $b->nombre = $b->bonusType ? $b->bonusType->nombre : '-';
                    return $b;
                });
            @endphp
            @if($allBonuses->count() > 0)
            <div class="glass-card border border-white/40 p-6">
                <h3 class="text-sm font-semibold text-stone border-b border-stone/20 pb-2 mb-4">Bonificaciones</h3>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-stone/5 text-left">
                            <th class="px-3 py-2 font-medium text-stone">Tipo</th>
                            <th class="px-3 py-2 font-medium text-stone">Bonificación</th>
                            <th class="px-3 py-2 font-medium text-stone text-right">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allBonuses as $bonus)
                        <tr class="border-b border-stone/10">
                            <td class="px-3 py-2">
                                <span class="px-2 py-1 text-xs font-medium {{ $bonus->tipo === 'Remunerativa' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $bonus->tipo }}
                                </span>
                            </td>
                            <td class="px-3 py-2">{{ $bonus->nombre }}</td>
                            <td class="px-3 py-2 text-right font-medium">S/. {{ number_format($bonus->monto, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        <!-- QR del Empleado -->
        <div class="glass-card border border-white/40 p-6">
            <h3 class="text-sm font-semibold text-stone border-b border-stone/20 pb-2 mb-4">QR del Empleado</h3>
            <div class="flex flex-col sm:flex-row items-center gap-6">
                @if($employee->qr_code)
                <img
                    src="{{ \App\Support\QrGenerator::pngDataUri($employee->qr_code, 180) }}"
                    alt="QR de {{ $employee->nombres }} {{ $employee->apellidos }}"
                    class="w-44 h-44 bg-white p-2 border border-stone/20"
                >
                @else
                <div class="w-44 h-44 bg-stone/10 border border-stone/20 flex items-center justify-center text-stoneLight text-xs text-center p-2">
                    Sin QR generado
                </div>
                @endif
                <div class="text-sm space-y-2 text-center sm:text-left">
                    <p><span class="text-stoneLight">Código:</span> <code class="text-jet">{{ $employee->qr_code ?? '-' }}</code></p>
                    <p class="text-stoneLight">Este código identifica al empleado para marcar asistencia escaneando el QR con la aplicación.</p>
                    <div class="flex gap-2 justify-center sm:justify-start">
                        <a href="{{ route('qr-employees.view', $employee->id) }}" class="px-4 py-2 bg-jet text-almondDark text-sm font-medium hover:bg-jetLight">
                            <i class="fas fa-id-card mr-2"></i>Ver carnet
                        </a>
                        <a href="{{ route('qr-employees.print', $employee->id) }}" class="px-4 py-2 border border-stone/30 text-stone text-sm font-medium hover:bg-stone/10">
                            <i class="fas fa-print mr-2"></i>Imprimir
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection