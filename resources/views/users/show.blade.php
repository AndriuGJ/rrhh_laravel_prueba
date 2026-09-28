@extends('layouts.dashboard')

@section('title', 'Ver Usuario')

@section('main-content')
    <div class="space-y-4 text-brandBlack">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('users.index') }}" class="p-2 hover:bg-stone/10">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <p class="text-[0.55rem] uppercase tracking-[0.6em] text-jetDark">Sistema</p>
                    <h1 class="text-2xl font-semibold">Detalles del Usuario</h1>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('users.edit', $user->id) }}" class="px-4 py-2 bg-stone text-white text-sm font-medium hover:bg-stoneLight">
                    <i class="fas fa-edit mr-2"></i>Editar
                </a>
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-medium hover:bg-red-700" onclick="return confirm('¿Eliminar usuario?')">
                        <i class="fas fa-trash mr-2"></i>Eliminar
                    </button>
                </form>
            </div>
        </div>

        <!-- Info Card -->
        <div class="glass-card border border-white/40 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <h3 class="text-sm font-semibold text-stone border-b border-stone/20 pb-2">Información Personal</h3>
                    <div class="text-sm space-y-2">
                        <p><span class="text-stoneLight">Nombre:</span> {{ $user->name }}</p>
                        <p><span class="text-stoneLight">Email:</span> {{ $user->email }}</p>
                        <p><span class="text-stoneLight">Estado:</span> 
                            @if($user->estado)
                            <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-medium">Activo</span>
                            @else
                            <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-800 text-xs font-medium">Inactivo</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="space-y-3">
                    <h3 class="text-sm font-semibold text-stone border-b border-stone/20 pb-2">Rol y Permissions</h3>
                    <div class="text-sm space-y-2">
                        <p><span class="text-stoneLight">Rol:</span> {{ $user->rol?->nombre ?? 'Sin rol assigned' }}</p>
                        <p><span class="text-stoneLight">Fecha de creación:</span> {{ $user->created_at->format('d/m/Y H:i') }}</p>
                        <p><span class="text-stoneLight">Última actualización:</span> {{ $user->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection