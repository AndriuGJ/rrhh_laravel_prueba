@extends('layouts.dashboard')

@section('title', 'Editar Asistencia')

@section('main-content')
    <div class="space-y-4 text-brandBlack">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('attendances.index') }}" class="p-2 hover:bg-stone/10">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <p class="text-[0.55rem] uppercase tracking-[0.6em] text-jetDark">RRHH</p>
                <h1 class="text-2xl font-semibold">Editar Asistencia</h1>
            </div>
        </div>

        <!-- Form -->
        <div class="glass-card border border-white/40 p-6">
            <form method="POST" action="{{ route('attendances.update', $attendance->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Empleado -->
                <div>
                    <h3 class="text-sm font-semibold text-stone mb-4 pb-2 border-b border-stone/20">
                        <i class="fas fa-user mr-2"></i>Empleado
                    </h3>
                    <select name="employee_id" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90" required>
                        <option value="">Seleccionar...</option>
                        @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ old('employee_id', $attendance->employee_id) == $emp->id ? 'selected' : '' }}>
                            {{ $emp->nombres }} {{ $emp->apellidos }} - {{ $emp->dni }}
                        </option>
                        @endforeach
                    </select>
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
                            <input type="date" name="fecha" value="{{ old('fecha', $attendance->fecha?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90" required>
                            @error('fecha') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Turno *</label>
                            <select name="turno" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90" required>
                                <option value="MANANA" {{ old('turno', $attendance->turno) == 'MANANA' ? 'selected' : '' }}>Mañana (08:00 - 13:00)</option>
                                <option value="TARDE" {{ old('turno', $attendance->turno) == 'TARDE' ? 'selected' : '' }}>Tarde (14:00 - 17:00)</option>
                                <option value="COMPLETO" {{ old('turno', $attendance->turno) == 'COMPLETO' ? 'selected' : '' }}>Completo</option>
                            </select>
                            @error('turno') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Hora Entrada</label>
                            <input type="time" name="hora_entrada" value="{{ old('hora_entrada', $attendance->hora_entrada?->format('H:i')) }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Hora Salida</label>
                            <input type="time" name="hora_salida" value="{{ old('hora_salida', $attendance->hora_salida?->format('H:i')) }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                    </div>
                </div>

                <!-- Estado Actual -->
                <div class="p-4 bg-stone/5">
                    <h4 class="text-sm font-medium text-stone mb-2">Estado Actual</h4>
                    <div class="flex gap-4">
                        <span class="px-3 py-1 bg-stone text-white text-sm">
                            Estado: {{ $attendance->estado }}
                        </span>
                        @if($attendance->minutos_tardanza > 0)
                        <span class="px-3 py-1 bg-yellow-500 text-white text-sm">
                            Tardanza: {{ $attendance->minutos_tardanza }} min
                        </span>
                        @endif
                        @if($attendance->es_falta_turno)
                        <span class="px-3 py-1 bg-red-500 text-white text-sm">
                            Falta por turno
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Licencia (opcional) -->
                <div>
                    <h3 class="text-sm font-semibold text-stone mb-4 pb-2 border-b border-stone/20">
                        <i class="fas fa-file-medical mr-2"></i>Licencia (opcional)
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Tipo Licencia</label>
                            <select name="tipo_licencia" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                                <option value="">Seleccionar...</option>
                                <option value="CG" {{ old('tipo_licencia', $attendance->tipo_licencia) == 'CG' ? 'selected' : '' }}>Con Goce (CG)</option>
                                <option value="SH" {{ old('tipo_licencia', $attendance->tipo_licencia) == 'SH' ? 'selected' : '' }}>Sin Goce (SH)</option>
                                <option value="ME" {{ old('tipo_licencia', $attendance->tipo_licencia) == 'ME' ? 'selected' : '' }}>Médica</option>
                                <option value="PA" {{ old('tipo_licencia', $attendance->tipo_licencia) == 'PA' ? 'selected' : '' }}>Paterna</option>
                                <option value="PN" {{ old('tipo_licencia', $attendance->tipo_licencia) == 'PN' ? 'selected' : '' }}>Prenatal</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Fecha Fin Licencia</label>
                            <input type="date" name="fecha_fin_licencia" value="{{ old('fecha_fin_licencia', $attendance->fecha_fin_licencia) }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Observación</label>
                            <input type="text" name="observacion" value="{{ old('observacion', $attendance->observacion) }}" placeholder="Observación..." class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-4 pt-4 border-t border-stone/20">
                    <a href="{{ route('attendances.index') }}" class="px-6 py-2 border border-stone/30 text-stone font-medium hover:bg-stone/10">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2 bg-jet text-almondDark font-medium hover:bg-jetLight">
                        <i class="fas fa-save mr-2"></i>Actualizar Asistencia
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection