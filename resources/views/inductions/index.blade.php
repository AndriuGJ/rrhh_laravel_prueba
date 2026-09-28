@extends('layouts.dashboard')

@section('title', 'Inducciones')

@section('main-content')
    <div class="space-y-4 text-brandBlack">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <p class="text-[0.55rem] uppercase tracking-[0.6em] text-jetDark">Gestión de personal</p>
                <h1 class="text-2xl font-semibold">Conformidades de Inducción</h1>
            </div>
            <a href="{{ route('inductions.create') }}" class="inline-flex items-center px-4 py-2 bg-jet text-almondDark text-sm font-medium hover:bg-jetLight">
                <i class="fas fa-plus mr-2"></i>Nueva Inducción
            </a>
        </div>

        <div class="glass-card border border-white/40 p-4">
            <form method="GET" action="{{ route('inductions.index') }}" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar por empleado..." class="w-full px-4 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                </div>
                <div class="w-full md:w-40">
                    <select name="tipo" class="w-full px-4 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        <option value="">Todos los tipos</option>
                        <option value="GENERAL" {{ ($tipo ?? '') == 'GENERAL' ? 'selected' : '' }}>General</option>
                        <option value="ESPECIFICA" {{ ($tipo ?? '') == 'ESPECIFICA' ? 'selected' : '' }}>Específica</option>
                        <option value="SEGURIDAD" {{ ($tipo ?? '') == 'SEGURIDAD' ? 'selected' : '' }}>Seguridad</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-stone text-white text-sm font-medium hover:bg-stoneLight">Buscar</button>
                <a href="{{ route('inductions.index') }}" class="px-4 py-2 border border-stone/30 text-stone text-sm font-medium hover:bg-stone/10">Limpiar</a>
            </form>
        </div>

        @if(session('success'))
        <div class="p-4 bg-green-50 border-l-4 border-green-600 text-green-700">
            {{ session('success') }}
        </div>
        @endif

        <div class="glass-card border border-white/40 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-stone/10 text-stone text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 text-left">N°</th>
                        <th class="px-4 py-3 text-left">Empleado</th>
                        <th class="px-4 py-3 text-left">Tipo</th>
                        <th class="px-4 py-3 text-left">Responsable</th>
                        <th class="px-4 py-3 text-left">Fecha</th>
                        <th class="px-4 py-3 text-left">Estado</th>
                        <th class="px-4 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone/20">
                    @forelse($inductions as $index => $induction)
                    <tr class="hover:bg-khaki/5">
                        <td class="px-4 py-3">{{ $inductions->firstItem() + $index }}</td>
                        <td class="px-4 py-3">{{ $induction->employee->nombres }} {{ $induction->employee->apellidos }}</td>
                        <td class="px-4 py-3">
                            @switch($induction->tipo)
                                @case('GENERAL') <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs">General</span> @break
                                @case('ESPECIFICA') <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs">Específica</span> @break
                                @case('SEGURIDAD') <span class="px-2 py-1 bg-red-100 text-red-800 text-xs">Seguridad</span> @break
                            @endswitch
                        </td>
                        <td class="px-4 py-3">{{ $induction->responsable ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $induction->fecha ? \Carbon\Carbon::parse($induction->fecha)->format('d/m/Y') : '-' }}</td>
                        <td class="px-4 py-3">
                            @if($induction->estado)
                            <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-medium">Activo</span>
                            @else
                            <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-800 text-xs font-medium">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('inductions.show', $induction->id) }}" class="p-1.5 text-stone hover:text-jet"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('inductions.edit', $induction->id) }}" class="p-1.5 text-stone hover:text-jet"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('inductions.destroy', $induction->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-stone hover:text-red-600" onclick="return confirm('¿Eliminar?')"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-stoneLight">No se encontraron inducciones.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex justify-center">
            {{ $inductions->appends(request()->query())->links() }}
        </div>
    </div>
@endsection