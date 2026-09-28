@extends('layouts.dashboard')

@section('title', 'Ver Inducción')

@section('main-content')
    <div class="space-y-4 text-brandBlack">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('inductions.index') }}" class="p-2 hover:bg-stone/10"><i class="fas fa-arrow-left"></i></a>
                <div>
                    <p class="text-[0.55rem] uppercase tracking-[0.6em] text-jetDark">Gestión de personal</p>
                    <h1 class="text-2xl font-semibold">Detalles de Inducción</h1>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('inductions.edit', $induction->id) }}" class="px-4 py-2 bg-stone text-white text-sm font-medium hover:bg-stoneLight"><i class="fas fa-edit mr-2"></i>Editar</a>
                <form action="{{ route('inductions.destroy', $induction->id) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-medium hover:bg-red-700" onclick="return confirm('¿Eliminar?')"><i class="fas fa-trash mr-2"></i>Eliminar</button>
                </form>
            </div>
        </div>

        <div class="glass-card border border-white/40 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="space-y-3">
                    <h3 class="text-sm font-semibold text-stone border-b border-stone/20 pb-2">Inducción</h3>
                    <div class="text-sm space-y-2">
                        <p><span class="text-stoneLight">Tipo:</span> 
                            @switch($induction->tipo)
                                @case('GENERAL') <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs">General</span> @break
                                @case('ESPECIFICA') <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs">Específica</span> @break
                                @case('SEGURIDAD') <span class="px-2 py-1 bg-red-100 text-red-800 text-xs">Seguridad</span> @break
                            @endswitch
                        </p>
                        <p><span class="text-stoneLight">Responsable:</span> {{ $induction->responsable ?? '-' }}</p>
                        <p><span class="text-stoneLight">Fecha:</span> {{ $induction->fecha ? \Carbon\Carbon::parse($induction->fecha)->format('d/m/Y') : '-' }}</p>
                        <p><span class="text-stoneLight">Estado:</span> 
                            @if($induction->estado)
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
                        <p><span class="text-stoneLight">Nombre:</span> {{ $induction->employee->nombres }} {{ $induction->employee->apellidos }}</p>
                        <p><span class="text-stoneLight">DNI:</span> {{ $induction->employee->dni }}</p>
                        <p><span class="text-stoneLight">Cargo:</span> {{ $induction->employee->cargo ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection