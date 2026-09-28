@extends('layouts.dashboard')

@section('title', 'Generar Planilla')

@section('main-content')
    <div class="space-y-4 text-brandBlack">
        <div class="flex items-center gap-4">
            <a href="{{ route('payrolls.index') }}" class="p-2 hover:bg-stone/10">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <p class="text-[0.55rem] uppercase tracking-[0.6em] text-jetDark">RRHH</p>
                <h1 class="text-2xl font-semibold">Generar Planilla</h1>
            </div>
        </div>

        <div class="glass-card border border-white/40 p-6">
            <form method="POST" action="{{ route('payrolls.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-stone mb-1">Período</label>
                        <input type="month" name="periodo" value="{{ $periodo }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-stone mb-1">Régimen Laboral</label>
                        <select name="regimen" id="regimenFilter" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                            <option value="">Todos</option>
                            @foreach($regimenes as $r)
                            <option value="{{ $r }}" {{ $regimen == $r ? 'selected' : '' }}>{{ $r }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-stone mb-1">Buscar Empleado</label>
                        <input type="text" id="searchInput" value="" placeholder="Escribe para buscar..." class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        <div id="searchResults" class="hidden absolute z-10 w-full max-h-48 overflow-y-auto bg-white border border-stone/30 mt-1 shadow-lg"></div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" id="clearAll" class="px-4 py-2 border border-stone/30 text-stone text-sm font-medium hover:bg-stone/10">
                        <i class="fas fa-times mr-2"></i>Limpiar
                    </button>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-stone mb-4 pb-2 border-b border-stone/20">
                        <i class="fas fa-users mr-2"></i>Empleados
                        <span class="text-xs font-normal text-stoneLight ml-2">(<span id="selectedCount">0</span> seleccionados)</span>
                    </h3>
                    <div class="border border-stone/30 max-h-96 overflow-y-auto">
                        <table class="w-full">
                            <thead class="bg-stone/5 sticky top-0">
                                <tr class="text-left text-xs font-medium text-stone">
                                    <th class="px-4 py-2 w-10">
                                        <input type="checkbox" id="selectAllVisible" class="w-4 h-4">
                                    </th>
                                    <th class="px-4 py-2">Nombre</th>
                                    <th class="px-4 py-2">DNI</th>
                                    <th class="px-4 py-2">Régimen</th>
                                    <th class="px-4 py-2">Sueldo</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone/10">
                                @forelse($employees as $emp)
                                <tr class="employee-row hover:bg-stone/5" data-regimen="{{ $emp->regimen_laboral }}" data-name="{{ strtolower($emp->nombres . ' ' . $emp->apellidos) }}" data-dni="{{ $emp->dni }}">
                                    <td class="px-4 py-2">
                                        <input type="checkbox" name="employee_ids[]" value="{{ $emp->id }}" class="employee-checkbox w-4 h-4">
                                    </td>
                                    <td class="px-4 py-2">{{ $emp->nombres }} {{ $emp->apellidos }}</td>
                                    <td class="px-4 py-2 font-mono">{{ $emp->dni }}</td>
                                    <td class="px-4 py-2 text-xs">{{ $emp->regimen_laboral }}</td>
                                    <td class="px-4 py-2">S/. {{ number_format($emp->sueldo_basico, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-stoneLight">No hay empleados activos.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-4 border-t border-stone/20">
                    <a href="{{ route('payrolls.index') }}" class="px-6 py-2 border border-stone/30 text-stone font-medium hover:bg-stone/10">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2 bg-jet text-almondDark font-medium hover:bg-jetLight">
                        <i class="fas fa-calculator mr-2"></i>Generar Planilla
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/payroll-select.js') }}"></script>
@endsection