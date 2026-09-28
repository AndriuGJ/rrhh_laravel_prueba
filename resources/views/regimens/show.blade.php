@extends('layouts.dashboard')

@section('title', 'Ver Régimen')

@section('main-content')
    <div class="space-y-4 text-brandBlack">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('regimens.index') }}" class="p-2 hover:bg-stone/10">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <p class="text-[0.55rem] uppercase tracking-[0.6em] text-jetDark">Configuración</p>
                    <h1 class="text-2xl font-semibold">Detalles del Régimen</h1>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('regimens.edit', $regimen->id) }}" class="px-4 py-2 bg-stone text-white text-sm font-medium hover:bg-stoneLight">
                    <i class="fas fa-edit mr-2"></i>Editar
                </a>
                <form action="{{ route('regimens.destroy', $regimen->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-medium hover:bg-red-700" onclick="return confirm('¿Está seguro de eliminar este régimen?')">
                        <i class="fas fa-trash mr-2"></i>Eliminar
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
        <div class="p-4 bg-green-50 border-l-4 border-green-600 text-green-700">
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="p-4 bg-red-50 border-l-4 border-red-600 text-red-700">
            {{ session('error') }}
        </div>
        @endif

        <div class="glass-card border border-white/40 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <h3 class="text-sm font-semibold text-stone border-b border-stone/20 pb-2">Datos del Régimen</h3>
                    <div class="text-sm space-y-2">
                        <p><span class="text-stoneLight">Nombre:</span> <span class="font-mono">{{ $regimen->nombre }}</span></p>
                        <p><span class="text-stoneLight">Descripción:</span> {{ $regimen->descripcion ?: '-' }}</p>
                        <p>
                            <span class="text-stoneLight">Estado:</span>
                            @if($regimen->estado)
                            <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-medium">Activo</span>
                            @else
                            <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-800 text-xs font-medium">Inactivo</span>
                            @endif
                        </p>
                        <p><span class="text-stoneLight">Creado:</span> {{ $regimen->created_at?->format('d/m/Y H:i') ?? '-' }}</p>
                        <p><span class="text-stoneLight">Actualizado:</span> {{ $regimen->updated_at?->format('d/m/Y H:i') ?? '-' }}</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <h3 class="text-sm font-semibold text-stone border-b border-stone/20 pb-2">Uso</h3>
                    <div class="text-sm space-y-2">
                        <p>
                            <span class="text-stoneLight">Empleados con este régimen:</span>
                            <span class="font-semibold">{{ $uso }}</span>
                        </p>
                        @if($uso > 0)
                        <p class="text-xs text-stoneLight">No se puede eliminar mientras tenga empleados asignados. Puede desactivarlo.</p>
                        @else
                        <p class="text-xs text-stoneLight">Sin empleados asignados: puede eliminarse.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
