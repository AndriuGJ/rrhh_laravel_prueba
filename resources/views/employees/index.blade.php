@extends('layouts.dashboard')

@section('title', 'Empleados')

@section('main-content')
    <div class="space-y-4 text-brandBlack">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <p class="text-[0.55rem] uppercase tracking-[0.6em] text-jetDark">Gestión de personal</p>
                <h1 class="text-2xl font-semibold">Listado de Empleados</h1>
            </div>
            <a href="{{ route('employees.create') }}" class="inline-flex items-center px-4 py-2 bg-jet text-almondDark text-sm font-medium hover:bg-jetLight">
                <i class="fas fa-plus mr-2"></i>Nuevo Empleado
            </a>
        </div>

        <!-- Filters -->
        <div class="glass-card border border-white/40 p-4">
            <form method="GET" action="{{ route('employees.index') }}" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ $search ?? '' }}"
                        placeholder="Buscar por nombres, apellidos, DNI o email..." 
                        class="w-full px-4 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90"
                    >
                </div>
                <div class="w-full md:w-40">
                    <select name="estado" class="w-full px-4 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        <option value="">Todos</option>
                        <option value="1" {{ ($estado ?? '') === '1' ? 'selected' : '' }}>Activos</option>
                        <option value="0" {{ ($estado ?? '') === '0' ? 'selected' : '' }}>Inactivos</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-stone text-white text-sm font-medium hover:bg-stoneLight">
                    <i class="fas fa-search mr-2"></i>Buscar
                </button>
                <a href="{{ route('employees.index') }}" class="px-4 py-2 border border-stone/30 text-stone text-sm font-medium hover:bg-stone/10">
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
                        <th class="px-4 py-3 text-left">Cargo</th>
                        <th class="px-4 py-3 text-left">Régimen</th>
                        <th class="px-4 py-3 text-left">Sueldo</th>
                        <th class="px-4 py-3 text-left">Estado</th>
                        <th class="px-4 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone/20">
                    @forelse($employees as $index => $employee)
                    <tr class="hover:bg-khaki/5">
                        <td class="px-4 py-3">{{ $employees->firstItem() + $index }}</td>
                        <td class="px-4 py-3 font-mono">{{ $employee->dni }}</td>
                        <td class="px-4 py-3">{{ $employee->nombres }}</td>
                        <td class="px-4 py-3">{{ $employee->apellidos }}</td>
                        <td class="px-4 py-3">{{ $employee->cargo ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $employee->regimen_laboral ? \Str::limit($employee->regimen_laboral, 20) : '-' }}</td>
                        <td class="px-4 py-3">S/. {{ number_format($employee->sueldo_basico, 2) }}</td>
                        <td class="px-4 py-3">
                            @if($employee->activo)
                            <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-medium">Activo</span>
                            @else
                            <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-800 text-xs font-medium">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('employees.show', $employee->id) }}" class="p-1.5 text-stone hover:text-jet" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('employees.edit', $employee->id) }}" class="p-1.5 text-stone hover:text-jet" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('dependents.byEmployee', $employee->id) }}" class="p-1.5 text-stone hover:text-jet" title="Derechohabientes">
                                    <i class="fas fa-users"></i>
                                </a>
                                <a href="{{ route('inductions.byEmployee', $employee->id) }}" class="p-1.5 text-stone hover:text-jet" title="Inducciones">
                                    <i class="fas fa-clipboard-check"></i>
                                </a>
                                <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-stone hover:text-red-600" title="Eliminar" onclick="return confirm('¿Está seguro de eliminar este empleado?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-center text-stoneLight">
                            No se encontraron employees.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $employees->appends(request()->query())->links() }}
        </div>
    </div>
@endsection