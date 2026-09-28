@extends('layouts.dashboard')

@section('title', 'Usuarios')

@section('main-content')
    <div class="space-y-4 text-brandBlack">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div>
                    <p class="text-[0.55rem] uppercase tracking-[0.6em] text-jetDark">Sistema</p>
                    <h1 class="text-2xl font-semibold">Usuarios</h1>
                </div>
            </div>
            <a href="{{ route('users.create') }}" class="px-4 py-2 bg-jet text-almondDark text-sm font-medium hover:bg-jetLight">
                <i class="fas fa-plus mr-2"></i>Nuevo Usuario
            </a>
        </div>

        <!-- Search -->
        <div class="glass-card border border-white/40 p-4">
            <form method="GET" action="{{ route('users.index') }}" class="flex gap-4">
                <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por nombre o email..." class="flex-1 px-3 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90">
                <button type="submit" class="px-4 py-2 bg-stone text-white text-sm font-medium hover:bg-stoneLight">
                    <i class="fas fa-search mr-2"></i>Buscar
                </button>
            </form>
        </div>

        <!-- Table -->
        <div class="glass-card border border-white/40 overflow-hidden">
            <table class="w-full">
                <thead class="bg-stone/5">
                    <tr class="text-left text-xs font-medium text-stone uppercase tracking-wider">
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Rol</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone/10">
                    @forelse($users as $user)
                    <tr class="hover:bg-stone/5">
                        <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-stoneLight">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1">{{ $user->rol?->nombre ?? 'Sin rol' }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @if($user->estado)
                            <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-medium">Activo</span>
                            @else
                            <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-800 text-xs font-medium">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('users.show', $user->id) }}" class="text-stone hover:text-jet">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('users.edit', $user->id) }}" class="text-stone hover:text-jet">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('¿Eliminar usuario?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-stoneLight">No hay usuarios registrados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="flex justify-center">
            {{ $users->links() }}
        </div>
        @endif
    </div>
@endsection