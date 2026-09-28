@extends('layouts.dashboard')

@section('title', 'Editar Usuario')

@section('main-content')
    <div class="space-y-4 text-brandBlack">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('users.index') }}" class="p-2 hover:bg-stone/10">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <p class="text-[0.55rem] uppercase tracking-[0.6em] text-jetDark">Sistema</p>
                <h1 class="text-2xl font-semibold">Editar Usuario</h1>
            </div>
        </div>

        <!-- Form -->
        <div class="glass-card border border-white/40 p-6">
            <form method="POST" action="{{ route('users.update', $user->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-stone mb-1">Nombre *</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-stone mb-1">Email *</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90" required>
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-stone mb-1">Nueva Contraseña (dejar en blanco para mantener)</label>
                        <input type="password" name="password" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-stone mb-1">Rol</label>
                        <select name="rol_id" class="w-full px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                            <option value="">Seleccionar...</option>
                            @foreach(App\Models\Role::where('estado', true)->get() as $rol)
                            <option value="{{ $rol->id }}" {{ old('rol_id', $user->rol_id) == $rol->id ? 'selected' : '' }}>{{ $rol->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="estado" id="estado" value="1" {{ old('estado', $user->estado) ? 'checked' : '' }} class="w-4 h-4">
                        <label for="estado" class="text-sm">Usuario activo</label>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-4 pt-4 border-t border-stone/20">
                    <a href="{{ route('users.index') }}" class="px-6 py-2 border border-stone/30 text-stone font-medium hover:bg-stone/10">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2 bg-jet text-almondDark font-medium hover:bg-jetLight">
                        <i class="fas fa-save mr-2"></i>Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection