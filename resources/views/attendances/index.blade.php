@extends('layouts.dashboard')

@section('title', 'Asistencia')

@section('main-content')
    <div class="space-y-4 text-brandBlack">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div>
                    <p class="text-[0.55rem] uppercase tracking-[0.6em] text-jetDark">RRHH</p>
                    <h1 class="text-2xl font-semibold">Control de Asistencia</h1>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('attendances.import') }}" class="px-4 py-2 bg-green-600 text-white text-sm font-medium hover:bg-green-700">
                    <i class="fas fa-file-excel mr-2"></i>Importar Excel
                </a>
                <button type="button" onclick="document.getElementById('deleteRangeModal').showModal()" class="px-4 py-2 bg-red-600 text-white text-sm font-medium hover:bg-red-700">
                    <i class="fas fa-trash mr-2"></i>Eliminar Rango
                </button>
                <a href="{{ route('attendances.create') }}" class="px-4 py-2 bg-jet text-almondDark text-sm font-medium hover:bg-jetLight">
                    <i class="fas fa-plus mr-2"></i>Registrar Asistencia
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="glass-card border border-white/40 p-4">
            <form method="GET" action="{{ route('attendances.index') }}" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-medium text-stone mb-1">Buscar empleado</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Nombre, apellido o DNI..." class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                </div>
                <div>
                    <label class="block text-xs font-medium text-stone mb-1">Fecha inicio</label>
                    <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                </div>
                <div>
                    <label class="block text-xs font-medium text-stone mb-1">Fecha fin</label>
                    <input type="date" name="fecha_fin" value="{{ $fechaFin }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                </div>
                <button type="submit" class="px-4 py-2 bg-stone text-white text-sm font-medium hover:bg-stoneLight">
                    <i class="fas fa-search mr-2"></i>Filtrar
                </button>
            </form>
        </div>

        <!-- Table -->
        <div class="glass-card border border-white/40 overflow-hidden">
            <table class="w-full">
                <thead class="bg-stone/5">
                    <tr class="text-left text-xs font-medium text-stone uppercase tracking-wider">
                        <th class="px-4 py-3">Fecha</th>
                        <th class="px-4 py-3">Empleado</th>
                        <th class="px-4 py-3">Turno</th>
                        <th class="px-4 py-3">Entrada</th>
                        <th class="px-4 py-3">Salida</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3">Tardanza</th>
                        <th class="px-4 py-3">Descuento</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone/10">
                    @forelse($attendances as $att)
                    <tr class="hover:bg-stone/5">
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($att->fecha)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="text-sm font-medium">{{ $att->employee->nombres }} {{ $att->employee->apellidos }}</div>
                            <div class="text-xs text-stoneLight">{{ $att->employee->dni }}</div>
                        </td>
                        <td class="px-4 py-3">
                            @switch($att->turno)
                                @case('MANANA') <span class="text-xs">Mañana</span> @break
                                @case('TARDE') <span class="text-xs">Tarde</span> @break
                                @case('COMPLETO') <span class="text-xs">Completo</span> @break
                            @endswitch
                        </td>
                        <td class="px-4 py-3">{{ $att->hora_entrada ? \Carbon\Carbon::parse($att->hora_entrada)->format('H:i') : '-' }}</td>
                        <td class="px-4 py-3">{{ $att->hora_salida ? \Carbon\Carbon::parse($att->hora_salida)->format('H:i') : '-' }}</td>
                        <td class="px-4 py-3">
                            @switch($att->estado)
                                @case('PUNTUAL')
                                <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-medium">Puntual</span>
                                @break
                                @case('TARDE')
                                <span class="inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium">Tarde</span>
                                @break
                                @case('FALTA')
                                <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-800 text-xs font-medium">Falta</span>
                                @break
                                @case('NO_APORTE')
                                <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium">No Aporte</span>
                                @break
                            @endswitch
                            @if($att->origen && $att->origen !== 'manual')
                            <span class="inline-flex items-center px-1.5 py-0.5 bg-stone/10 text-stone text-[0.6rem] font-medium ml-1 uppercase" title="Registro origen: {{ $att->origen }}">{{ $att->origen }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($att->minutos_tardanza > 0)
                            <span class="text-red-600">{{ $att->minutos_tardanza }} min</span>
                            @else
                            <span class="text-stoneLight">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($att->descuento > 0)
                            <span class="text-red-600 font-medium">S/. {{ number_format($att->descuento, 2) }}</span>
                            @else
                            <span class="text-stoneLight">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('attendances.edit', $att->id) }}" class="text-stone hover:text-jet">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('attendances.destroy', $att->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('¿Eliminar asistencia?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-center text-stoneLight">No hay registros de asistencia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($attendances->hasPages())
        <div class="flex justify-center">
            {{ $attendances->links() }}
        </div>
        @endif
    </div>

    <!-- Delete Range Modal -->
    <dialog id="deleteRangeModal" class="modal p-6 bg-white rounded-lg shadow-xl">
        <h3 class="text-lg font-semibold mb-4">Eliminar Asistencias por Rango</h3>
        <form method="POST" action="{{ route('attendances.destroyRange') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-stone mb-1">Fecha inicio</label>
                    <input type="date" name="fecha_inicio" required class="w-full px-3 py-2 border border-stone/30">
                </div>
                <div>
                    <label class="block text-sm font-medium text-stone mb-1">Fecha fin</label>
                    <input type="date" name="fecha_fin" required class="w-full px-3 py-2 border border-stone/30">
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" onclick="document.getElementById('deleteRangeModal').close()" class="px-4 py-2 border border-stone/30 text-stone">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white" onclick="return confirm('¿Eliminar todas las asistencias en este rango?')">Eliminar</button>
                </div>
            </div>
        </form>
    </dialog>
@endsection