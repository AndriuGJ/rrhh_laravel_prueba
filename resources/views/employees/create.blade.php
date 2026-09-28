@extends('layouts.dashboard')

@section('title', 'Nuevo Empleado')

@section('main-content')
    <div class="space-y-4 text-brandBlack">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('employees.index') }}" class="p-2 hover:bg-stone/10">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <p class="text-[0.55rem] uppercase tracking-[0.6em] text-jetDark">Gestión de personal</p>
                <h1 class="text-2xl font-semibold">Nuevo Empleado</h1>
            </div>
        </div>

        <!-- Form -->
        <div class="glass-card border border-white/40 p-6">
            <form method="POST" action="{{ route('employees.store') }}" class="space-y-6" enctype="multipart/form-data">
                @csrf

                <!-- Datos Personales -->
                <div>
                    <h3 class="text-sm font-semibold text-stone mb-4 pb-2 border-b border-stone/20">
                        <i class="fas fa-user mr-2"></i>Datos Personales
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="lg:col-span-2">
                            <label class="block text-xs font-medium text-stone mb-1">Nombres *</label>
                            <input type="text" name="nombres" value="{{ old('nombres') }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90" required>
                            @error('nombres') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="lg:col-span-2">
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
                            <label class="block text-xs font-medium text-stone mb-1">Email *</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90" required>
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Teléfono</label>
                            <input type="text" name="telefono" value="{{ old('telefono') }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90" maxlength="9">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div class="md:col-span-2 lg:col-span-4">
                            <label class="block text-xs font-medium text-stone mb-1">Dirección</label>
                            <input type="text" name="direccion" value="{{ old('direccion') }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                    </div>
                </div>

                <!-- Datos Laborales -->
                <div>
                    <h3 class="text-sm font-semibold text-stone mb-4 pb-2 border-b border-stone/20">
                        <i class="fas fa-briefcase mr-2"></i>Datos Laborales
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Cargo</label>
                            <input type="text" name="cargo" value="{{ old('cargo') }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Nivel</label>
                            <input type="text" name="nivel" value="{{ old('nivel') }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Sueldo Básico *</label>
                            <input type="number" name="sueldo_basico" id="sueldo_basico" value="{{ old('sueldo_basico') }}" step="0.01" min="0" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90" required>
                            @error('sueldo_basico') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Régimen Laboral</label>
                            <select name="regimen_laboral" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                                <option value="">Seleccionar...</option>
                                @foreach($regimenes as $regimen)
                                <option value="{{ $regimen->nombre }}" {{ old('regimen_laboral') == $regimen->nombre ? 'selected' : '' }}>
                                    {{ $regimen->descripcion ?: $regimen->nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Fecha de Ingreso</label>
                            <input type="date" name="fecha_ingreso" value="{{ old('fecha_ingreso') }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Fecha de Cese</label>
                            <input type="date" name="fecha_cese" value="{{ old('fecha_cese') }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Documento de Ingreso</label>
                            <input type="text" name="documento_ingreso" value="{{ old('documento_ingreso') }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Archivo de Documento</label>
                            <input type="file" name="documento_archivo" accept=".pdf,.docx,.png,.jpg,.jpeg" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                            <p class="text-xs text-stoneLight mt-1">Formatos permitidos: PDF, DOCX, PNG, JPG (máx 5MB)</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Rubro</label>
                            <input type="text" name="rubro" value="{{ old('rubro') }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                    </div>
                </div>

                <!-- Datos Pensionarios -->
                <div>
                    <h3 class="text-sm font-semibold text-stone mb-4 pb-2 border-b border-stone/20">
                        <i class="fas fa-university mr-2"></i>Datos Pensionarios
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Régimen Pensionario</label>
                            <select name="regimen_pensionario" id="regimen_pensionario" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                                <option value="AFP" {{ old('regimen_pensionario') == 'AFP' ? 'selected' : '' }}>AFP</option>
                                <option value="ONP" {{ old('regimen_pensionario') == 'ONP' ? 'selected' : '' }}>ONP</option>
                                <option value="NO_APORTE" {{ old('regimen_pensionario') == 'NO_APORTE' ? 'selected' : '' }}>NO APORTE</option>
                            </select>
                        </div>
                        <div id="afp_especifica_group">
                            <label class="block text-xs font-medium text-stone mb-1">AFP Específica</label>
                            <select name="afp_especifica" id="afp_especifica" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                                <option value="">Seleccionar...</option>
                                <option value="HABITAT" {{ old('afp_especifica') == 'HABITAT' ? 'selected' : '' }}>Habitat</option>
                                <option value="INTEGRA" {{ old('afp_especifica') == 'INTEGRA' ? 'selected' : '' }}>Integra</option>
                                <option value="PRIMA" {{ old('afp_especifica') == 'PRIMA' ? 'selected' : '' }}>Prima</option>
                                <option value="PROFUTURO" {{ old('afp_especifica') == 'PROFUTURO' ? 'selected' : '' }}>Profuturo</option>
                            </select>
                        </div>
                        <div id="afp_tipo_comision_group">
                            <label class="block text-xs font-medium text-stone mb-1">Tipo Comisión AFP</label>
                            <select name="afp_tipo_comision" id="afp_tipo_comision" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                                <option value="FLUJO" {{ old('afp_tipo_comision') == 'FLUJO' ? 'selected' : '' }}>Flujo</option>
                                <option value="MIXTA" {{ old('afp_tipo_comision') == 'MIXTA' ? 'selected' : '' }}>Mixta</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">CUSPP</label>
                            <input type="text" name="cuspp" value="{{ old('cuspp') }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div id="afp_tiene_jubilacion_group">
                            <div class="flex items-center gap-2 mt-2">
                                <input type="checkbox" name="afp_tiene_jubilacion" id="afp_tiene_jubilacion" value="1" {{ old('afp_tiene_jubilacion') ? 'checked' : '' }} class="w-4 h-4">
                                <label for="afp_tiene_jubilacion" class="text-sm">Tiene jubilación</label>
                            </div>
                        </div>
                    </div>
                    <div id="afp_percentage" class="mt-4 p-3 bg-stone/5 border border-stone/20 rounded">
                        <span class="text-sm font-medium">Porcentaje de descuento: </span>
                        <span id="afp_percentage_display" class="text-lg font-bold text-jet">0.00%</span>
                    </div>
                </div>

                <!-- Datos Bancarios -->
                <div>
                    <h3 class="text-sm font-semibold text-stone mb-4 pb-2 border-b border-stone/20">
                        <i class="fas fa-credit-card mr-2"></i>Datos Bancarios
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Banco</label>
                            <input type="text" name="banco" value="{{ old('banco') }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Cuenta Bancaria</label>
                            <input type="text" name="cuenta_bancaria" value="{{ old('cuenta_bancaria') }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Número CTS</label>
                            <input type="text" name="numero_cts" value="{{ old('numero_cts') }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Meta</label>
                            <input type="text" name="meta" value="{{ old('meta') }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                    </div>
                </div>

                <!-- Asignación Familiar -->
                <div>
                    <h3 class="text-sm font-semibold text-stone mb-4 pb-2 border-b border-stone/20">
                        <i class="fas fa-users mr-2"></i>Asignación Familiar
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <div class="flex items-center gap-2 mt-2">
                                <input type="checkbox" name="tiene_asignacion_familiar" id="tiene_asignacion_familiar" value="1" {{ old('tiene_asignacion_familiar') ? 'checked' : '' }} class="w-4 h-4">
                                <label for="tiene_asignacion_familiar" class="text-sm">Tiene asignación familiar</label>
                            </div>
                        </div>
                        <div id="asignacion_porcentaje_group">
                            <label class="block text-xs font-medium text-stone mb-1">Porcentaje (%)</label>
                            <input type="number" name="asignacion_familiar_porcentaje" id="asignacion_familiar_porcentaje" value="{{ old('asignacion_familiar_porcentaje', 10) }}" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div id="asignacion_monto_group">
                            <label class="block text-xs font-medium text-stone mb-1">Monto Base (RMV)</label>
                            <input type="number" name="asignacion_familiar_monto" id="asignacion_familiar_monto" value="{{ old('asignacion_familiar_monto', 1025) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                    </div>
                    <div id="asignacion_result" class="mt-3 p-3 bg-stone/5 border border-stone/20 rounded">
                        <span class="text-sm font-medium">Monto a pagar: </span>
                        <span id="asignacion_display" class="text-lg font-bold text-green-600">S/. 0.00</span>
                    </div>
                </div>

                <!-- Renta Quinta -->
                <div>
                    <h3 class="text-sm font-semibold text-stone mb-4 pb-2 border-b border-stone/20">
                        <i class="fas fa-percentage mr-2"></i>Renta Quinta Categoría
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div id="renta_quinta_group">
                            <div class="flex items-center gap-2 mt-2">
                                <input type="checkbox" name="aplica_renta_quinta" id="aplica_renta_quinta" value="1" {{ old('aplica_renta_quinta') ? 'checked' : '' }} class="w-4 h-4">
                                <label for="aplica_renta_quinta" class="text-sm">Aplica renta quinta</label>
                            </div>
                            <p class="text-xs text-stoneLight mt-1">Solo si básico > S/. 2,750</p>
                        </div>
                        <div id="renta_porcentaje_group" class="hidden">
                            <label class="block text-xs font-medium text-stone mb-1">Porcentaje (%)</label>
                            <input type="number" name="renta_quinta_porcentaje" id="renta_quinta_porcentaje" value="{{ old('renta_quinta_porcentaje', 8) }}" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div id="renta_uit_group" class="hidden">
                            <label class="block text-xs font-medium text-stone mb-1">UIT</label>
                            <input type="number" name="renta_quinta_uit" id="renta_quinta_uit" value="{{ old('renta_quinta_uit', 38500) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                    </div>
                    <div id="renta_result" class="hidden mt-3 p-3 bg-stone/5 border border-stone/20 rounded">
                        <span class="text-sm font-medium">Renta quinta mensual: </span>
                        <span id="renta_display" class="text-lg font-bold text-red-600">S/. 0.00</span>
                        <span class="text-xs text-stoneLight ml-2">(Ingreso anual: <span id="renta_anual">0</span> - UIT: <span id="renta_base">0</span>)</span>
                    </div>
                </div>

                <!-- Bonificaciones -->
                <div>
                    <h3 class="text-sm font-semibold text-stone mb-4 pb-2 border-b border-stone/20">
                        <i class="fas fa-gift mr-2"></i>Bonificaciones
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-xs font-semibold text-stone mb-2">Remunerativas</h4>
                            @foreach($bonusTypes->where('es_remunerativo', true) as $bonus)
                            <div class="flex items-center gap-2 mb-2">
                                <input type="checkbox" name="bonuses[{{ $bonus->id }}][selected]" id="bonus_{{ $bonus->id }}" value="1" class="w-4 h-4">
                                <label for="bonus_{{ $bonus->id }}" class="text-sm">{{ $bonus->nombre }}</label>
                                <input type="hidden" name="bonuses[{{ $bonus->id }}][bonus_type_id]" value="{{ $bonus->id }}">
                                <input type="number" name="bonuses[{{ $bonus->id }}][monto]" value="" placeholder="Monto" step="0.01" min="0" class="w-24 px-2 py-1 border border-stone/30 text-sm">
                            </div>
                            @endforeach
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-stone mb-2">No Remunerativas</h4>
                            @foreach($bonusTypes->where('es_remunerativo', false) as $bonus)
                            <div class="flex items-center gap-2 mb-2">
                                <input type="checkbox" name="bonuses[{{ $bonus->id }}][selected]" id="bonus_{{ $bonus->id }}" value="1" class="w-4 h-4">
                                <label for="bonus_{{ $bonus->id }}" class="text-sm">{{ $bonus->nombre }}</label>
                                <input type="hidden" name="bonuses[{{ $bonus->id }}][bonus_type_id]" value="{{ $bonus->id }}">
                                <input type="number" name="bonuses[{{ $bonus->id }}][monto]" value="" placeholder="Monto" step="0.01" min="0" class="w-24 px-2 py-1 border border-stone/30 text-sm">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Estado -->
                <div>
                    <h3 class="text-sm font-semibold text-stone mb-4 pb-2 border-b border-stone/20">
                        <i class="fas fa-toggle-on mr-2"></i>Estado
                    </h3>
                    <div class="flex flex-wrap gap-4">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="activo" id="activo" value="1" {{ old('activo', true) ? 'checked' : '' }} class="w-4 h-4">
                            <label for="activo" class="text-sm">Empleado activo</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="conformidad_tregistro" id="conformidad_tregistro" value="1" {{ old('conformidad_tregistro') ? 'checked' : '' }} class="w-4 h-4">
                            <label for="conformidad_tregistro" class="text-sm">Conformidad T-Registro</label>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-4 pt-4 border-t border-stone/20">
                    <a href="{{ route('employees.index') }}" class="px-6 py-2 border border-stone/30 text-stone font-medium hover:bg-stone/10">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2 bg-jet text-almondDark font-medium hover:bg-jetLight">
                        <i class="fas fa-save mr-2"></i>Guardar Empleado
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/employee-pension.js') }}"></script>
@endsection
