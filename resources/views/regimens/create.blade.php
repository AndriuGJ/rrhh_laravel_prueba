@extends('layouts.dashboard')

@section('title', 'Nuevo Régimen')

@section('main-content')
    <div class="space-y-4 text-brandBlack">
        <div class="flex items-center gap-4">
            <a href="{{ route('regimens.index') }}" class="p-2 hover:bg-stone/10">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <p class="text-[0.55rem] uppercase tracking-[0.6em] text-jetDark">Configuración</p>
                <h1 class="text-2xl font-semibold">Nuevo Régimen</h1>
            </div>
        </div>

        <div class="glass-card border border-white/40 p-6">
            <form method="POST" action="{{ route('regimens.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-stone mb-1">Nombre (código) *</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" maxlength="150" placeholder="Ej. DL 276" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90" required>
                        @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        <p class="text-xs text-stoneLight mt-1">Valor corto y único que se guardará en el empleado (ej. DL 276, CAS).</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-stone mb-1">Descripción</label>
                        <input type="text" name="descripcion" value="{{ old('descripcion') }}" maxlength="255" placeholder="Ej. Decreto Legislativo 276 (DL 276)" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        @error('descripcion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-stone mb-1">Estado *</label>
                        <input type="hidden" name="estado" value="0">
                        <label class="flex items-center gap-2 text-sm cursor-pointer">
                            <input type="checkbox" name="estado" id="estado" value="1" {{ old('estado', '1') == '1' ? 'checked' : '' }} class="w-4 h-4 accent-jet">
                            Activo
                        </label>
                        @error('estado') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-4 border-t border-stone/20">
                    <a href="{{ route('regimens.index') }}" class="px-6 py-2 border border-stone/30 text-stone font-medium hover:bg-stone/10">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2 bg-jet text-almondDark font-medium hover:bg-jetLight">
                        <i class="fas fa-save mr-2"></i>Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
