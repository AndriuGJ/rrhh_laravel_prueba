@extends('layouts.dashboard')

@section('title', 'Nuevo Derechohabiente')

@section('main-content')
    <div class="space-y-4 text-brandBlack">
        <div class="flex items-center gap-4">
            <a href="{{ route('dependents.index') }}" class="p-2 hover:bg-stone/10">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <p class="text-[0.55rem] uppercase tracking-[0.6em] text-jetDark">Gestión de personal</p>
                <h1 class="text-2xl font-semibold">Nuevo Derechohabiente</h1>
            </div>
        </div>

        <div class="glass-card border border-white/40 p-6">
            <form method="POST" action="{{ route('dependents.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-stone mb-1">Empleado *</label>
                        <input type="text" id="employeeSearch" placeholder="Buscar empleado..." class="w-full px-3 py-2 mb-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        <select name="employee_id" id="employeeSelect" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90" required>
                            <option value="">Seleccionar empleado...</option>
                            @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }} data-search="{{ strtolower($employee->nombres . ' ' . $employee->apellidos . ' ' . $employee->dni) }}">
                                {{ $employee->nombres }} {{ $employee->apellidos }} - {{ $employee->dni }}
                            </option>
                            @endforeach
                        </select>
                        @error('employee_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-stone mb-1">Tipo de Vínculo *</label>
<select name="tipo_vinculo" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90" required>
                                <option value="">Seleccionar...</option>
                                <option value="H" {{ old('tipo_vinculo') == 'H' ? 'selected' : '' }}>Hijo/a</option>
                                <option value="C" {{ old('tipo_vinculo') == 'C' ? 'selected' : '' }}>Cónyuge</option>
                                <option value="P" {{ old('tipo_vinculo') == 'P' ? 'selected' : '' }}>Padre</option>
                                <option value="M" {{ old('tipo_vinculo') == 'M' ? 'selected' : '' }}>Madre</option>
                                <option value="PE" {{ old('tipo_vinculo') == 'PE' ? 'selected' : '' }}>Hermano/a</option>
                                <option value="HM" {{ old('tipo_vinculo') == 'HM' ? 'selected' : '' }}>Hijo Mayor de Edad con Discapacidad</option>
                            </select>
                        @error('tipo_vinculo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-stone mb-1">Nombres *</label>
                        <input type="text" name="nombres" value="{{ old('nombres') }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90" required>
                        @error('nombres') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-stone mb-1">Apellidos *</label>
                        <input type="text" name="apellidos" value="{{ old('apellidos') }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90" required>
                        @error('apellidos') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-stone mb-1">DNI *</label>
                        <input type="text" name="dni" value="{{ old('dni') }}" maxlength="8" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90" required>
                        @error('dni') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-stone mb-1">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-4 border-t border-stone/20">
                    <a href="{{ route('dependents.index') }}" class="px-6 py-2 border border-stone/30 text-stone font-medium hover:bg-stone/10">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2 bg-jet text-almondDark font-medium hover:bg-jetLight">
                        <i class="fas fa-save mr-2"></i>Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('employeeSearch').addEventListener('keyup', function() {
            var searchText = this.value.toLowerCase();
            var select = document.getElementById('employeeSelect');
            var options = select.getElementsByTagName('option');
            
            for (var i = 1; i < options.length; i++) {
                var optionText = options[i].getAttribute('data-search') || '';
                if (optionText.indexOf(searchText) !== -1) {
                    options[i].style.display = '';
                } else {
                    options[i].style.display = 'none';
                }
            }
        });
    </script>
@endsection