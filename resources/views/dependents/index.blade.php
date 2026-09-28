@extends('layouts.dashboard')

@section('title', 'Derechohabientes')

@section('main-content')
    <div class="space-y-4 text-brandBlack">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <p class="text-[0.55rem] uppercase tracking-[0.6em] text-jetDark">Gestión de personal</p>
                <h1 class="text-2xl font-semibold">Derechohabientes</h1>
            </div>
            <a href="{{ route('dependents.create') }}" class="inline-flex items-center px-4 py-2 bg-jet text-almondDark text-sm font-medium hover:bg-jetLight">
                <i class="fas fa-plus mr-2"></i>Nuevo Derechohabiente
            </a>
        </div>

        <!-- Filters -->
        <div class="glass-card border border-white/40 p-4">
            <form method="GET" action="{{ route('dependents.index') }}" class="flex gap-4">
                <div class="flex-1">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ $search ?? '' }}"
                        placeholder="Buscar por nombres, apellidos o DNI..." 
                        class="w-full px-4 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90"
                    >
                </div>
                <button type="submit" class="px-4 py-2 bg-stone text-white text-sm font-medium hover:bg-stoneLight">
                    <i class="fas fa-search mr-2"></i>Buscar
                </button>
                <a href="{{ route('dependents.index') }}" class="px-4 py-2 border border-stone/30 text-stone text-sm font-medium hover:bg-stone/10">
                    Limpiar
                </a>
            </form>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="p-4 bg-green-50 border-l-4 border-green-600 text-green-700">
            {{ session('success') }}
        </div>
        @endif

        <!-- Table -->
        <div class="glass-card border border-white/40 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-stone/10 text-stone text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 text-left">N°</th>
                        <th class="px-4 py-3 text-left">DNI</th>
                        <th class="px-4 py-3 text-left">Nombres</th>
                        <th class="px-4 py-3 text-left">Apellidos</th>
                        <th class="px-4 py-3 text-left">Vínculo</th>
                        <th class="px-4 py-3 text-left">Empleado</th>
                        <th class="px-4 py-3 text-left">Estado</th>
                        <th class="px-4 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone/20">
                    @forelse($dependents as $index => $dependent)
                    <tr class="hover:bg-khaki/5">
                        <td class="px-4 py-3">{{ $dependents->firstItem() + $index }}</td>
                        <td class="px-4 py-3 font-mono">{{ $dependent->dni }}</td>
                        <td class="px-4 py-3">{{ $dependent->nombres }}</td>
                        <td class="px-4 py-3">{{ $dependent->apellidos }}</td>
                        <td class="px-4 py-3">
                            @switch($dependent->tipo_vinculo)
                                @case('H') <span class="text-xs">Hijo/a</span> @break
                                @case('C') <span class="text-xs">Cónyuge</span> @break
                                @case('P') <span class="text-xs">Padre</span> @break
                                @case('M') <span class="text-xs">Madre</span> @break
                                @case('PE') <span class="text-xs">Hermano/a</span> @break
                                @case('HM') <span class="text-xs">Hijo Mayor con Discapacidad</span> @break
                            @endswitch
                        </td>
                        <td class="px-4 py-3">{{ $dependent->employee->nombres }} {{ $dependent->employee->apellidos }}</td>
                        <td class="px-4 py-3">
                            @if($dependent->estado)
                            <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-medium">Activo</span>
                            @else
                            <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-800 text-xs font-medium">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('dependents.show', $dependent->id) }}" class="p-1.5 text-stone hover:text-jet" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('dependents.edit', $dependent->id) }}" class="p-1.5 text-stone hover:text-jet" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('dependents.destroy', $dependent->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-stone hover:text-red-600" title="Eliminar" onclick="return confirm('¿Está seguro de eliminar este derechohabiente?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-stoneLight">
                            No se encontraron derechohabientes.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $dependents->appends(request()->query())->links() }}
        </div>
    </div>
@endsection