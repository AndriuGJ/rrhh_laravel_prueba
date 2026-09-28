@extends('layouts.dashboard')

@section('title', 'Editar Empleado')

@section('main-content')
    <div class="space-y-4 text-brandBlack">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('employees.index') }}" class="p-2 hover:bg-stone/10">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <p class="text-[0.55rem] uppercase tracking-[0.6em] text-jetDark">Gestión de personal</p>
                <h1 class="text-2xl font-semibold">Editar Empleado</h1>
            </div>
        </div>

        <!-- Form -->
        <div class="glass-card border border-white/40 p-6">
            <form method="POST" action="{{ route('employees.update', $employee->id) }}" class="space-y-6" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Datos Personales -->
                <div>
                    <h3 class="text-sm font-semibold text-stone mb-4 pb-2 border-b border-stone/20">
                        <i class="fas fa-user mr-2"></i>Datos Personales
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="lg:col-span-2">
                            <label class="block text-xs font-medium text-stone mb-1">Nombres *</label>
                            <input type="text" name="nombres" value="{{ old('nombres', $employee->nombres) }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90" required>
                            @error('nombres') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="lg:col-span-2">
                            <label class="block text-xs font-medium text-stone mb-1">Apellidos *</label>
                            <input type="text" name="apellidos" value="{{ old('apellidos', $employee->apellidos) }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90" required>
                            @error('apellidos') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">DNI *</label>
                            <input type="text" name="dni" value="{{ old('dni', $employee->dni) }}" maxlength="8" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90" required>
                            @error('dni') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Email *</label>
                            <input type="email" name="email" value="{{ old('email', $employee->email) }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90" required>
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Teléfono</label>
                            <input type="text" name="telefono" value="{{ old('telefono', $employee->telefono) }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90" maxlength="9">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $employee->fecha_nacimiento?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div class="md:col-span-2 lg:col-span-4">
                            <label class="block text-xs font-medium text-stone mb-1">Dirección</label>
                            <input type="text" name="direccion" value="{{ old('direccion', $employee->direccion) }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
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
                            <input type="text" name="cargo" value="{{ old('cargo', $employee->cargo) }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Nivel</label>
                            <input type="text" name="nivel" value="{{ old('nivel', $employee->nivel) }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Sueldo Básico *</label>
                            <input type="number" name="sueldo_basico" id="sueldo_basico" value="{{ old('sueldo_basico', $employee->sueldo_basico) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90" required>
                            @error('sueldo_basico') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Régimen Laboral</label>
                            @php
                                $regimenActual = old('regimen_laboral', $employee->regimen_laboral);
                            @endphp
                            <select name="regimen_laboral" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                                <option value="">Seleccionar...</option>
                                @foreach($regimenes as $regimen)
                                <option value="{{ $regimen->nombre }}" {{ $regimenActual == $regimen->nombre ? 'selected' : '' }}>
                                    {{ $regimen->descripcion ?: $regimen->nombre }}
                                </option>
                                @endforeach
                                @if($regimenActual && !$regimenes->contains('nombre', $regimenActual))
                                <option value="{{ $regimenActual }}" selected>{{ $regimenActual }} (no está en el catálogo)</option>
                                @endif
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Fecha de Ingreso</label>
                            <input type="date" name="fecha_ingreso" value="{{ old('fecha_ingreso', $employee->fecha_ingreso?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Fecha de Cese</label>
                            <input type="date" name="fecha_cese" value="{{ old('fecha_cese', $employee->fecha_cese?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Documento de Ingreso</label>
                            <input type="text" name="documento_ingreso" value="{{ old('documento_ingreso', $employee->documento_ingreso) }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Archivo de Documento</label>
                            @if($employee->documento_path)
                                <div class="flex items-center gap-2 mb-2 p-2 bg-green-50 border border-green-200 rounded">
                                    <i class="fas fa-file text-green-600"></i>
                                    <span class="text-sm text-green-800">Archivo actual subido</span>
                                </div>
                                <div class="flex items-center gap-2 mb-2">
                                    <input type="checkbox" name="eliminar_documento" id="eliminar_documento" value="1" class="w-4 h-4">
                                    <label for="eliminar_documento" class="text-sm text-red-600">Eliminar archivo actual</label>
                                </div>
                            @endif
                            <input type="file" name="documento_archivo" accept=".pdf,.docx,.png,.jpg,.jpeg" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                            @if($employee->documento_path)
                                <p class="text-xs text-stoneLight mt-1">Subir nuevo archivo para reemplazar el actual</p>
                            @else
                                <p class="text-xs text-stoneLight mt-1">Formatos permitidos: PDF, DOCX, PNG, JPG (máx 5MB)</p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Rubro</label>
                            <input type="text" name="rubro" value="{{ old('rubro', $employee->rubro) }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
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
                                <option value="AFP" {{ old('regimen_pensionario', $employee->regimen_pensionario) == 'AFP' ? 'selected' : '' }}>AFP</option>
                                <option value="ONP" {{ old('regimen_pensionario', $employee->regimen_pensionario) == 'ONP' ? 'selected' : '' }}>ONP</option>
                                <option value="NO_APORTE" {{ old('regimen_pensionario', $employee->regimen_pensionario) == 'NO_APORTE' ? 'selected' : '' }}>NO APORTE</option>
                            </select>
                        </div>
                        <div id="afp_especifica_group">
                            <label class="block text-xs font-medium text-stone mb-1">AFP Específica</label>
                            <select name="afp_especifica" id="afp_especifica" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                                <option value="">Seleccionar...</option>
                                <option value="HABITAT" {{ old('afp_especifica', $employee->afp_especifica) == 'HABITAT' ? 'selected' : '' }}>Habitat</option>
                                <option value="INTEGRA" {{ old('afp_especifica', $employee->afp_especifica) == 'INTEGRA' ? 'selected' : '' }}>Integra</option>
                                <option value="PRIMA" {{ old('afp_especifica', $employee->afp_especifica) == 'PRIMA' ? 'selected' : '' }}>Prima</option>
                                <option value="PROFUTURO" {{ old('afp_especifica', $employee->afp_especifica) == 'PROFUTURO' ? 'selected' : '' }}>Profuturo</option>
                            </select>
                        </div>
                        <div id="afp_tipo_comision_group">
                            <label class="block text-xs font-medium text-stone mb-1">Tipo Comisión AFP</label>
                            <select name="afp_tipo_comision" id="afp_tipo_comision" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                                <option value="FLUJO" {{ old('afp_tipo_comision', $employee->afp_tipo_comision) == 'FLUJO' ? 'selected' : '' }}>Flujo</option>
                                <option value="MIXTA" {{ old('afp_tipo_comision', $employee->afp_tipo_comision) == 'MIXTA' ? 'selected' : '' }}>Mixta</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">CUSPP</label>
                            <input type="text" name="cuspp" value="{{ old('cuspp', $employee->cuspp) }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div id="afp_tiene_jubilacion_group">
                            <div class="flex items-center gap-2 mt-2">
                                <input type="checkbox" name="afp_tiene_jubilacion" id="afp_tiene_jubilacion" value="1" {{ old('afp_tiene_jubilacion', $employee->afp_tiene_jubilacion) ? 'checked' : '' }} class="w-4 h-4">
                                <label for="afp_tiene_jubilacion" class="text-sm">Tiene jubilación</label>
                            </div>
                        </div>
                    </div>
                    <div id="afp percentage" class="mt-4 p-3 bg-stone/5 border border-stone/20 rounded">
                        <span class="text-sm font-medium">Porcentaje de descuento: </span>
                        <span id="afp_percentage_display" class="text-lg font-bold text-jet">
                            @if($employee->regimen_pensionario === 'ONP')
                                13.00%
                            @elseif($employee->regimen_pensionario === 'NO_APORTE')
                                0.00%
                            @else
                                {{ number_format($employee->sueldo_basico > 0 ? $employee->afp_comision_total / $employee->sueldo_basico * 100 : 0, 2) }}%
                            @endif
                        </span>
                        <span class="text-xs text-stoneLight ml-2">
                            @if($employee->regimen_pensionario === 'ONP')
                                (ONP 13%)
                            @elseif($employee->regimen_pensionario === 'NO_APORTE')
                                (Sin aporte)
                            @else
                                (@if($employee->afp_tipo_comision === 'FLUJO')Flujo @else Mixta @endif)
                                @if($employee->afp_especifica)
                                    - {{ $employee->afp_especifica }}
                                @endif
                            @endif
                        </span>
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
                            <input type="text" name="banco" value="{{ old('banco', $employee->banco) }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Cuenta Bancaria</label>
                            <input type="text" name="cuenta_bancaria" value="{{ old('cuenta_bancaria', $employee->cuenta_bancaria) }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Número CTS</label>
                            <input type="text" name="numero_cts" value="{{ old('numero_cts', $employee->numero_cts) }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Meta</label>
                            <input type="text" name="meta" value="{{ old('meta', $employee->meta) }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
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
                                <input type="checkbox" name="tiene_asignacion_familiar" id="tiene_asignacion_familiar" value="1" {{ old('tiene_asignacion_familiar', $employee->tiene_asignacion_familiar) ? 'checked' : '' }} class="w-4 h-4">
                                <label for="tiene_asignacion_familiar" class="text-sm">Tiene asignación familiar</label>
                            </div>
                        </div>
                        <div id="asignacion_porcentaje_group">
                            <label class="block text-xs font-medium text-stone mb-1">Porcentaje (%)</label>
                            <input type="number" name="asignacion_familiar_porcentaje" id="asignacion_familiar_porcentaje" value="{{ old('asignacion_familiar_porcentaje', $employee->asignacion_familiar_porcentaje ?? 10) }}" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div id="asignacion_monto_group">
                            <label class="block text-xs font-medium text-stone mb-1">Monto Base (RMV)</label>
                            <input type="number" name="asignacion_familiar_monto" id="asignacion_familiar_monto" value="{{ old('asignacion_familiar_monto', $employee->asignacion_familiar_monto ?? 1025) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                    </div>
                    <div id="asignacion_result" class="mt-3 p-3 bg-stone/5 border border-stone/20 rounded">
                        <span class="text-sm font-medium">Monto a pagar: </span>
                        <span id="asignacion_display" class="text-lg font-bold text-green-600">S/. {{ number_format(($employee->asignacion_familiar_porcentaje ?? 10) / 100 * ($employee->asignacion_familiar_monto ?? 1025), 2) }}</span>
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
                                <input type="checkbox" name="aplica_renta_quinta" id="aplica_renta_quinta" value="1" {{ old('aplica_renta_quinta', $employee->aplica_renta_quinta) ? 'checked' : '' }} class="w-4 h-4">
                                <label for="aplica_renta_quinta" class="text-sm">Aplica renta quinta</label>
                            </div>
                            <p class="text-xs text-stoneLight mt-1">Solo si básico > S/. 2,750</p>
                        </div>
                        <div id="renta_porcentaje_group" class="{{ ($employee->sueldo_basico ?? 0) < 2750 ? 'hidden' : '' }}">
                            <label class="block text-xs font-medium text-stone mb-1">Porcentaje (%)</label>
                            <input type="number" name="renta_quinta_porcentaje" id="renta_quinta_porcentaje" value="{{ old('renta_quinta_porcentaje', $employee->renta_quinta_porcentaje ?? 8) }}" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div id="renta_uit_group" class="{{ ($employee->sueldo_basico ?? 0) < 2750 ? 'hidden' : '' }}">
                            <label class="block text-xs font-medium text-stone mb-1">UIT</label>
                            <input type="number" name="renta_quinta_uit" id="renta_quinta_uit" value="{{ old('renta_quinta_uit', $employee->renta_quinta_uit ?? 38500) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                    </div>
                    @php
                        $rq = 0;
                        $basico = $employee->sueldo_basico ?? 0;
                        if ($basico >= 2750 && $employee->aplica_renta_quinta) {
                            $ingresoAnual = ($basico * 12) + ($basico * 2);
                            $base = $ingresoAnual - ($employee->renta_quinta_uit ?? 38500);
                            if ($base > 0) {
                                $tasa = $base * (($employee->renta_quinta_porcentaje ?? 8) / 100);
                                $rq = $tasa / 12;
                            }
                        }
                    @endphp
                    <div id="renta_result" class="mt-3 p-3 bg-stone/5 border border-stone/20 rounded {{ ($employee->sueldo_basico ?? 0) < 2750 ? 'hidden' : '' }}">
                        <span class="text-sm font-medium">Renta quinta mensual: </span>
                        <span id="renta_display" class="text-lg font-bold text-red-600">S/. {{ number_format($rq, 2) }}</span>
                        <span class="text-xs text-stoneLight ml-2">(Ingreso anual: <span id="renta_anual">{{ number_format(($basico * 12) + ($basico * 2), 2) }}</span> - UIT: <span id="renta_base">{{ number_format(max(0, ($basico * 12 + $basico * 2) - ($employee->renta_quinta_uit ?? 38500)), 2) }}</span>)</span>
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
                                <input type="checkbox" name="bonuses[{{ $bonus->id }}][selected]" id="bonus_{{ $bonus->id }}" value="1" {{ isset($employeeBonusesMap[$bonus->id]) ? 'checked' : '' }} class="w-4 h-4">
                                <label for="bonus_{{ $bonus->id }}" class="text-sm">{{ $bonus->nombre }}</label>
                                <input type="hidden" name="bonuses[{{ $bonus->id }}][bonus_type_id]" value="{{ $bonus->id }}">
                                <input type="number" name="bonuses[{{ $bonus->id }}][monto]" value="{{ $employeeBonusesMap[$bonus->id] ?? '' }}" placeholder="Monto" step="0.01" min="0" class="w-24 px-2 py-1 border border-stone/30 text-sm">
                            </div>
                            @endforeach
                        </div>
                        <div>
                            <h4 class="text-xs font-semibold text-stone mb-2">No Remunerativas</h4>
                            @foreach($bonusTypes->where('es_remunerativo', false) as $bonus)
                            <div class="flex items-center gap-2 mb-2">
                                <input type="checkbox" name="bonuses[{{ $bonus->id }}][selected]" id="bonus_{{ $bonus->id }}" value="1" {{ isset($employeeBonusesMap[$bonus->id]) ? 'checked' : '' }} class="w-4 h-4">
                                <label for="bonus_{{ $bonus->id }}" class="text-sm">{{ $bonus->nombre }}</label>
                                <input type="hidden" name="bonuses[{{ $bonus->id }}][bonus_type_id]" value="{{ $bonus->id }}">
                                <input type="number" name="bonuses[{{ $bonus->id }}][monto]" value="{{ $employeeBonusesMap[$bonus->id] ?? '' }}" placeholder="Monto" step="0.01" min="0" class="w-24 px-2 py-1 border border-stone/30 text-sm">
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
                            <input type="checkbox" name="activo" id="activo" value="1" {{ old('activo', $employee->activo) ? 'checked' : '' }} class="w-4 h-4">
                            <label for="activo" class="text-sm">Empleado activo</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="conformidad_tregistro" id="conformidad_tregistro" value="1" {{ old('conformidad_tregistro', $employee->conformidad_tregistro) ? 'checked' : '' }} class="w-4 h-4">
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
                        <i class="fas fa-save mr-2"></i>Actualizar Empleado
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/employee-pension.js') }}"></script>
@endsection
