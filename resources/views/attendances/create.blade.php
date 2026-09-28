@extends('layouts.dashboard')

@section('title', 'Registrar Asistencia')

@section('main-content')
    <div class="space-y-4 text-brandBlack">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('attendances.index') }}" class="p-2 hover:bg-stone/10">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <p class="text-[0.55rem] uppercase tracking-[0.6em] text-jetDark">RRHH</p>
                <h1 class="text-2xl font-semibold">Registrar Asistencia</h1>
            </div>
        </div>

        <!-- Form -->
        <div class="glass-card border border-white/40 p-6">
            <form method="POST" action="{{ route('attendances.store') }}" class="space-y-6" id="attendanceForm">
                @csrf

                <!-- Buscador -->
                <div>
                    <h3 class="text-sm font-semibold text-stone mb-4 pb-2 border-b border-stone/20">
                        <i class="fas fa-search mr-2"></i>Buscar Empleado
                    </h3>
                    <div class="relative">
                        <input type="text" id="employeeSearch" placeholder="Buscar por nombre, apellido o DNI..." class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        <div id="searchResults" class="hidden absolute z-10 w-full mt-1 bg-white border border-stone/30 max-h-60 overflow-y-auto"></div>
                    </div>
                    <input type="hidden" name="employee_id" id="employee_id" required>
                    <div id="selectedEmployee" class="mt-2 p-3 bg-khaki/10 hidden">
                        <span class="font-medium" id="selectedEmployeeName"></span>
                        <span class="text-sm text-stoneLight" id="selectedEmployeeDni"></span>
                    </div>
                    @error('employee_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Datos de Asistencia -->
                <div>
                    <h3 class="text-sm font-semibold text-stone mb-4 pb-2 border-b border-stone/20">
                        <i class="fas fa-clock mr-2"></i>Datos de Asistencia
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Fecha *</label>
                            <input type="date" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90" required>
                            @error('fecha') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Turno *</label>
                            <select name="turno" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90" required>
                                <option value="MANANA" {{ old('turno') == 'MANANA' ? 'selected' : '' }}>Mañana (08:00 - 13:00)</option>
                                <option value="TARDE" {{ old('turno') == 'TARDE' ? 'selected' : '' }}>Tarde (14:00 - 17:00)</option>
                                <option value="COMPLETO" {{ old('turno', 'COMPLETO') == 'COMPLETO' ? 'selected' : '' }}>Completo</option>
                            </select>
                            @error('turno') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Hora Entrada</label>
                            <input type="time" name="hora_entrada" value="{{ old('hora_entrada') }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Hora Salida</label>
                            <input type="time" name="hora_salida" value="{{ old('hora_salida') }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                    </div>
                </div>

                <!-- Licencia (opcional) -->
                <div>
                    <h3 class="text-sm font-semibold text-stone mb-4 pb-2 border-b border-stone/20">
                        <i class="fas fa-file-medical mr-2"></i>Licencia
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Tipo Licencia</label>
                            <select name="tipo_licencia" id="tipo_licencia" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                                <option value="">Sin licencia</option>
                                <option value="CG" {{ old('tipo_licencia') == 'CG' ? 'selected' : '' }}>Con Goce (CG)</option>
                                <option value="SH" {{ old('tipo_licencia') == 'SH' ? 'selected' : '' }}>Sin Goce (SH)</option>
                                <option value="ME" {{ old('tipo_licencia') == 'ME' ? 'selected' : '' }}>Médica</option>
                                <option value="PA" {{ old('tipo_licencia') == 'PA' ? 'selected' : '' }}>Paterna</option>
                                <option value="PN" {{ old('tipo_licencia') == 'PN' ? 'selected' : '' }}>Prenatal</option>
                            </select>
                        </div>
                        <div id="fecha_fin_grupo">
                            <label class="block text-xs font-medium text-stone mb-1">Fecha Fin Licencia</label>
                            <input type="date" name="fecha_fin_licencia" id="fecha_fin_licencia" value="{{ old('fecha_fin_licencia') }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                            <p class="text-xs text-stoneLight mt-1">Si la licencia es por varios días</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Observación</label>
                            <input type="text" name="observacion" value="{{ old('observacion') }}" placeholder="Detalle de la licencia..." class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-4 pt-4 border-t border-stone/20">
                    <a href="{{ route('attendances.index') }}" class="px-6 py-2 border border-stone/30 text-stone font-medium hover:bg-stone/10">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2 bg-jet text-almondDark font-medium hover:bg-jetLight">
                        <i class="fas fa-save mr-2"></i>Registrar Asistencia
                    </button>
                </div>
            </form>
</div>
    </div>

    <script src="{{ asset('js/employee-search.js') }}"></script>
    <script src="{{ asset('js/attendance-license.js') }}"></script>
@endsection