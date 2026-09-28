@extends('layouts.dashboard')

@section('title', 'QR - Empleados')

@section('main-content')
    <div class="space-y-4 text-brandBlack">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <p class="text-[0.55rem] uppercase tracking-[0.6em] text-jetDark">Gestión de personal</p>
                <h1 class="text-2xl font-semibold">QR - Empleados</h1>
            </div>
        </div>

        <!-- Filters -->
        <div class="glass-card border border-white/40 p-4">
            <form method="GET" action="{{ route('qr-employees.index') }}" class="flex gap-4">
                <div class="flex-1">
                    <input
                        type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Buscar por nombre, apellido, DNI o cargo..."
                        class="w-full px-4 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90"
                    >
                </div>
                <button type="submit" class="px-4 py-2 bg-stone text-white text-sm font-medium hover:bg-stoneLight">
                    <i class="fas fa-search mr-2"></i>Buscar
                </button>
                <a href="{{ route('qr-employees.index') }}" class="px-4 py-2 border border-stone/30 text-stone text-sm font-medium hover:bg-stone/10">
                    Limpiar
                </a>
            </form>
        </div>

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
                        <th class="px-4 py-3 text-center">Estado</th>
                        <th class="px-4 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone/20">
                    @forelse($employees as $index => $employee)
                    <tr class="hover:bg-khaki/5">
                        <td class="px-4 py-3">{{ $employees->firstItem() + $index }}</td>
                        <td class="px-4 py-3">{{ $employee->dni }}</td>
                        <td class="px-4 py-3 font-medium">{{ $employee->nombres }}</td>
                        <td class="px-4 py-3">{{ $employee->apellidos }}</td>
                        <td class="px-4 py-3 text-stoneLight">{{ $employee->cargo ?? '-' }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($employee->activo)
                            <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-medium">Activo</span>
                            @else
                            <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-800 text-xs font-medium">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('qr-employees.view', $employee->id) }}" class="p-1.5 text-stone hover:text-jet" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('qr-employees.print', $employee->id) }}" class="p-1.5 text-stone hover:text-jet" title="Imprimir">
                                    <i class="fas fa-print"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-stoneLight">
                            No se encontraron empleados.
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
