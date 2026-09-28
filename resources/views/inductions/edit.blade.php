@extends('layouts.dashboard')

@section('title', 'Editar Inducción')

@section('main-content')
    <div class="space-y-4 text-brandBlack">
        <div class="flex items-center gap-4">
            <a href="{{ route('inductions.index') }}" class="p-2 hover:bg-stone/10"><i class="fas fa-arrow-left"></i></a>
            <div>
                <p class="text-[0.55rem] uppercase tracking-[0.6em] text-jetDark">Gestión de personal</p>
                <h1 class="text-2xl font-semibold">Editar Inducción</h1>
            </div>
        </div>

        <div class="glass-card border border-white/40 p-6">
            <form method="POST" action="{{ route('inductions.update', $induction->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-stone mb-1">Empleado *</label>
                        <input type="text" id="employeeSearch" placeholder="Buscar empleado..." class="w-full px-3 py-2 mb-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        <select name="employee_id" id="employeeSelect" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90" required>
                            <option value="">Seleccionar empleado...</option>
                            @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('employee_id', $induction->employee_id) == $employee->id ? 'selected' : '' }} data-search="{{ strtolower($employee->nombres . ' ' . $employee->apellidos . ' ' . $employee->dni) }}">
                                {{ $employee->nombres }} {{ $employee->apellidos }}
                            </option>
                            @endforeach
                        </select>
                        @error('employee_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-stone mb-1">Tipo *</label>
                        <select name="tipo" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90" required>
                            <option value="GENERAL" {{ old('tipo', $induction->tipo) == 'GENERAL' ? 'selected' : '' }}>General</option>
                            <option value="ESPECIFICA" {{ old('tipo', $induction->tipo) == 'ESPECIFICA' ? 'selected' : '' }}>Específica</option>
                            <option value="SEGURIDAD" {{ old('tipo', $induction->tipo) == 'SEGURIDAD' ? 'selected' : '' }}>Seguridad</option>
                        </select>
                        @error('tipo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-stone mb-1">Responsable</label>
                        <input type="text" name="responsable" value="{{ old('responsable', $induction->responsable) }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-stone mb-1">Fecha</label>
                        <input type="date" name="fecha" value="{{ old('fecha', $induction->fecha?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-4 border-t border-stone/20">
                    <a href="{{ route('inductions.index') }}" class="px-6 py-2 border border-stone/30 text-stone font-medium hover:bg-stone/10">Cancelar</a>
                    <button type="submit" class="px-6 py-2 bg-jet text-almondDark font-medium hover:bg-jetLight"><i class="fas fa-save mr-2"></i>Actualizar</button>
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