@extends('layouts.dashboard')

@section('title', 'Configuración')

@section('main-content')
    <div class="space-y-4 text-brandBlack">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <div>
                <p class="text-[0.55rem] uppercase tracking-[0.6em] text-jetDark">Sistema</p>
                <h1 class="text-2xl font-semibold">Configuración</h1>
            </div>
        </div>

        <!-- Form -->
        <div class="glass-card border border-white/40 p-6">
            <form method="POST" action="{{ route('configurations.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Tipo de Navegación -->
                <div>
                    <h3 class="text-sm font-semibold text-stone mb-4 pb-2 border-b border-stone/20">
                        <i class="fas fa-layer-group mr-2"></i>Tipo de Navegación
                    </h3>
                    @php($tipoNavegacion = old('tipo_navegacion', $configurations->firstWhere('clave', 'tipo_navegacion')->valor ?? 'header'))
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="flex items-start gap-3 p-4 border {{ $tipoNavegacion === 'header' ? 'border-jet bg-jet/5' : 'border-stone/30 bg-white/90' }} cursor-pointer">
                            <input type="radio" name="tipo_navegacion" value="header" {{ $tipoNavegacion === 'header' ? 'checked' : '' }} class="mt-1 accent-jet">
                            <div>
                                <p class="text-sm font-semibold text-brandBlack"><i class="fas fa-bars mr-2"></i>Barra superior (Header)</p>
                                <p class="text-xs text-stoneLight mt-1">El menú de navegación se muestra en la parte superior de la pantalla.</p>
                            </div>
                        </label>
                        <label class="flex items-start gap-3 p-4 border {{ $tipoNavegacion === 'sidebar' ? 'border-jet bg-jet/5' : 'border-stone/30 bg-white/90' }} cursor-pointer">
                            <input type="radio" name="tipo_navegacion" value="sidebar" {{ $tipoNavegacion === 'sidebar' ? 'checked' : '' }} class="mt-1 accent-jet">
                            <div>
                                <p class="text-sm font-semibold text-brandBlack"><i class="fas fa-bars mr-2"></i>Menú lateral (Sidebar)</p>
                                <p class="text-xs text-stoneLight mt-1">El menú se muestra fijo a la izquierda; en móvil se abre con el botón de menú.</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Datos de la Empresa -->
                <div>
                    <h3 class="text-sm font-semibold text-stone mb-4 pb-2 border-b border-stone/20">
                        <i class="fas fa-building mr-2"></i>Datos de la Empresa
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Nombre de la Empresa</label>
                            <input type="text" name="empresa_nombre" value="{{ old('empresa_nombre', \App\Support\Empresa::nombre()) }}" maxlength="255" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Nombre Corto</label>
                            <input type="text" name="empresa_nombre_corto" value="{{ old('empresa_nombre_corto', \App\Support\Empresa::nombreCorto()) }}" maxlength="255" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                            <p class="text-xs text-stoneLight mt-1">Se muestra en el menú lateral/superior y títulos.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">RUC</label>
                            <input type="text" name="empresa_ruc" value="{{ old('empresa_ruc', \App\Support\Empresa::ruc()) }}" maxlength="11" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Teléfono</label>
                            <input type="text" name="empresa_telefono" value="{{ old('empresa_telefono', \App\Support\Empresa::telefono()) }}" maxlength="50" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Dirección</label>
                            <input type="text" name="empresa_direccion" value="{{ old('empresa_direccion', \App\Support\Empresa::direccion()) }}" maxlength="255" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Correo de Contacto</label>
                            <input type="email" name="empresa_email" value="{{ old('empresa_email', \App\Support\Empresa::email()) }}" maxlength="255" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                    </div>
                </div>

                <!-- Logo e Imágenes -->
                <div>
                    <h3 class="text-sm font-semibold text-stone mb-4 pb-2 border-b border-stone/20">
                        <i class="fas fa-image mr-2"></i>Logo e Imágenes
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Logo Institucional</label>
                            <div class="flex items-center gap-4">
                                <img src="{{ \App\Support\Empresa::logoUrl() }}" alt="Logo actual" class="h-16 w-16 object-contain border border-stone/30 bg-white p-1 rounded">
                                <input type="file" name="logo" accept=".jpg,.jpeg,.png,.webp" class="text-xs text-stone">
                            </div>
                            <label class="flex items-center gap-2 mt-2 text-xs text-stone cursor-pointer">
                                <input type="checkbox" name="logo_default" value="1" class="accent-jet"> Restablecer logo por defecto
                            </label>
                            <p class="text-xs text-stoneLight mt-1">JPG, PNG o WEBP. Máx. 2 MB. Se usa en el menú, login, boletas y PDF.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Fondo del Login</label>
                            <div class="flex items-center gap-4">
                                <img src="{{ \App\Support\Empresa::fondoUrl() }}" alt="Fondo actual" class="h-16 w-24 object-cover border border-stone/30 rounded">
                                <input type="file" name="fondo_login" accept=".jpg,.jpeg,.png,.webp" class="text-xs text-stone">
                            </div>
                            <label class="flex items-center gap-2 mt-2 text-xs text-stone cursor-pointer">
                                <input type="checkbox" name="fondo_default" value="1" class="accent-jet"> Restablecer fondo por defecto
                            </label>
                            <p class="text-xs text-stoneLight mt-1">JPG, PNG o WEBP. Máx. 4 MB.</p>
                        </div>
                    </div>
                </div>

                <!-- Horarios Estándar -->
                <div>
                    <h3 class="text-sm font-semibold text-stone mb-4 pb-2 border-b border-stone/20">
                        <i class="fas fa-clock mr-2"></i>Horario Estándar
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Hora de Entrada</label>
                            <input type="time" name="horario_entrada" value="{{ old('horario_entrada', $configurations->firstWhere('clave', 'horario_entrada')->valor ?? '08:00') }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Hora de Salida</label>
                            <input type="time" name="horario_salida" value="{{ old('horario_salida', $configurations->firstWhere('clave', 'horario_salida')->valor ?? '17:00') }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                    </div>
                </div>

                <!-- Turno Mañana -->
                <div>
                    <h3 class="text-sm font-semibold text-stone mb-4 pb-2 border-b border-stone/20">
                        <i class="fas fa-sun mr-2"></i>Turno Mañana
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Entrada</label>
                            <input type="time" name="horario_entrada_manana" value="{{ old('horario_entrada_manana', $configurations->firstWhere('clave', 'horario_entrada_manana')->valor ?? '08:00') }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Salida</label>
                            <input type="time" name="horario_salida_manana" value="{{ old('horario_salida_manana', $configurations->firstWhere('clave', 'horario_salida_manana')->valor ?? '13:00') }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Tolerancia (minutos)</label>
                            <input type="number" name="tolerancia_manana" value="{{ old('tolerancia_manana', $configurations->firstWhere('clave', 'tolerancia_manana')->valor ?? 5) }}" min="0" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                    </div>
                </div>

                <!-- Turno Tarde -->
                <div>
                    <h3 class="text-sm font-semibold text-stone mb-4 pb-2 border-b border-stone/20">
                        <i class="fas fa-moon mr-2"></i>Turno Tarde
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Entrada</label>
                            <input type="time" name="horario_entrada_tarde" value="{{ old('horario_entrada_tarde', $configurations->firstWhere('clave', 'horario_entrada_tarde')->valor ?? '14:00') }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Salida</label>
                            <input type="time" name="horario_salida_tarde" value="{{ old('horario_salida_tarde', $configurations->firstWhere('clave', 'horario_salida_tarde')->valor ?? '17:00') }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Tolerancia (minutos)</label>
                            <input type="number" name="tolerancia_tarde" value="{{ old('tolerancia_tarde', $configurations->firstWhere('clave', 'tolerancia_tarde')->valor ?? 0) }}" min="0" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        </div>
                    </div>
                </div>

                <!-- Aportes del Empleador -->
                <div>
                    <h3 class="text-sm font-semibold text-stone mb-4 pb-2 border-b border-stone/20">
                        <i class="fas fa-building mr-2"></i>Aportes del Empleador
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Tasa ESSALUD (%)</label>
                            <input type="number" name="essalud_tasa" value="{{ old('essalud_tasa', $configurations->firstWhere('clave', 'essalud_tasa')->valor ?? 9) }}" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                            <p class="text-xs text-stoneLight mt-1">Porcentaje de aporte ESSALUD del empleador</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Remuneración Mínima Vital (RMV)</label>
                            <input type="number" name="rmv" value="{{ old('rmv', $configurations->firstWhere('clave', 'rmv')->valor ?? 1025) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                            <p class="text-xs text-stoneLight mt-1">Piso mínimo para cálculo de ESSALUD</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Tasa SCTR Salud (%)</label>
                            <input type="number" name="sctr_salud_tasa" value="{{ old('sctr_salud_tasa', $configurations->firstWhere('clave', 'sctr_salud_tasa')->valor ?? 0.8) }}" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                            <p class="text-xs text-stoneLight mt-1">Seguro contra riesgos de trabajo - Salud</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-stone mb-1">Tasa SCTR Pensiones (%)</label>
                            <input type="number" name="sctr_pensiones_tasa" value="{{ old('sctr_pensiones_tasa', $configurations->firstWhere('clave', 'sctr_pensiones_tasa')->valor ?? 0.8) }}" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                            <p class="text-xs text-stoneLight mt-1">Seguro contra riesgos de trabajo - Pensiones</p>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-4 pt-4 border-t border-stone/20">
                    <button type="submit" class="px-6 py-2 bg-jet text-almondDark font-medium hover:bg-jetLight">
                        <i class="fas fa-save mr-2"></i>Guardar Configuración
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection