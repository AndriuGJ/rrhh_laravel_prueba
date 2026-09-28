@extends('layouts.dashboard')

@section('title', 'Ver Derechohabiente')

@section('main-content')
    <div class="space-y-4 text-brandBlack">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('dependents.index') }}" class="p-2 hover:bg-stone/10">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <p class="text-[0.55rem] uppercase tracking-[0.6em] text-jetDark">Gestión de personal</p>
                    <h1 class="text-2xl font-semibold">Detalles del Derechohabiente</h1>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('dependents.edit', $dependent->id) }}" class="px-4 py-2 bg-stone text-white text-sm font-medium hover:bg-stoneLight">
                    <i class="fas fa-edit mr-2"></i>Editar
                </a>
                <form action="{{ route('dependents.destroy', $dependent->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-medium hover:bg-red-700" onclick="return confirm('¿Está seguro de eliminar este derechohabiente?')">
                        <i class="fas fa-trash mr-2"></i>Eliminar
                    </button>
                </form>
            </div>
        </div>

        <div class="glass-card border border-white/40 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="space-y-3">
                    <h3 class="text-sm font-semibold text-stone border-b border-stone/20 pb-2">Datos Personales</h3>
                    <div class="text-sm space-y-2">
                        <p><span class="text-stoneLight">DNI:</span> {{ $dependent->dni }}</p>
                        <p><span class="text-stoneLight">Nombres:</span> {{ $dependent->nombres }}</p>
                        <p><span class="text-stoneLight">Apellidos:</span> {{ $dependent->apellidos }}</p>
                        <p><span class="text-stoneLight">F. Nacimiento:</span> {{ $dependent->fecha_nacimiento ? \Carbon\Carbon::parse($dependent->fecha_nacimiento)->format('d/m/Y') : '-' }}</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <h3 class="text-sm font-semibold text-stone border-b border-stone/20 pb-2">Vínculo</h3>
                    <div class="text-sm space-y-2">
                        <p><span class="text-stoneLight">Tipo:</span> 
                            @switch($dependent->tipo_vinculo)
                                @case('H') Hijo/a @break
                                @case('C') Cónyuge @break
                                @case('P') Padre @break
                                @case('M') Madre @break
                                @case('PE') Hermano/a @break
                                @case('HM') Hijo Mayor de Edad con Discapacidad @break
                            @endswitch
                        </p>
                        <p><span class="text-stoneLight">Estado:</span> 
                            @if($dependent->estado)
                            <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-medium">Activo</span>
                            @else
                            <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-800 text-xs font-medium">Inactivo</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="space-y-3 lg:col-span-2">
                    <h3 class="text-sm font-semibold text-stone border-b border-stone/20 pb-2">Empleado</h3>
                    <div class="text-sm space-y-2">
                        <p><span class="text-stoneLight">Nombre:</span> {{ $dependent->employee->nombres }} {{ $dependent->employee->apellidos }}</p>
                        <p><span class="text-stoneLight">DNI:</span> {{ $dependent->employee->dni }}</p>
                        <p><span class="text-stoneLight">Cargo:</span> {{ $dependent->employee->cargo ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection