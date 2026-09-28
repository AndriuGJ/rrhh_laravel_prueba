@extends('layouts.dashboard')

@section('title', 'Régimenes')

@section('main-content')
    <div class="space-y-4 text-brandBlack">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <p class="text-[0.55rem] uppercase tracking-[0.6em] text-jetDark">Configuración</p>
                <h1 class="text-2xl font-semibold">Régimenes</h1>
            </div>
            <a href="{{ route('regimens.create') }}" class="inline-flex items-center px-4 py-2 bg-jet text-almondDark text-sm font-medium hover:bg-jetLight">
                <i class="fas fa-plus mr-2"></i>Nuevo Régimen
            </a>
        </div>

        <!-- Filters -->
        <div class="glass-card border border-white/40 p-4">
            <form method="GET" action="{{ route('regimens.index') }}" class="flex gap-4">
                <div class="flex-1">
                    <input
                        type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Buscar por nombre o descripción..."
                        class="w-full px-4 py-2 border border-stone/30 focus:border-jet focus:ring-0 bg-white/90"
                    >
                </div>
                <button type="submit" class="px-4 py-2 bg-stone text-white text-sm font-medium hover:bg-stoneLight">
                    <i class="fas fa-search mr-2"></i>Buscar
                </button>
                <a href="{{ route('regimens.index') }}" class="px-4 py-2 border border-stone/30 text-stone text-sm font-medium hover:bg-stone/10">
                    Limpiar
                </a>
            </form>
        </div>

        <!-- Messages -->
        @if(session('success'))
        <div class="p-4 bg-green-50 border-l-4 border-green-600 text-green-700">
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="p-4 bg-red-50 border-l-4 border-red-600 text-red-700">
            {{ session('error') }}
        </div>
        @endif

        <!-- Table -->
        <div class="glass-card border border-white/40 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-stone/10 text-stone text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 text-left">N°</th>
                        <th class="px-4 py-3 text-left">Nombre</th>
                        <th class="px-4 py-3 text-left">Descripción</th>
                        <th class="px-4 py-3 text-center">Estado</th>
                        <th class="px-4 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone/20">
                    @forelse($regimens as $index => $regimen)
                    <tr class="hover:bg-khaki/5">
                        <td class="px-4 py-3">{{ $regimens->firstItem() + $index }}</td>
                        <td class="px-4 py-3 font-mono font-medium">{{ $regimen->nombre }}</td>
                        <td class="px-4 py-3 text-stoneLight">{{ $regimen->descripcion ?: '-' }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($regimen->estado)
                            <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-medium">Activo</span>
                            @else
                            <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-800 text-xs font-medium">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('regimens.show', $regimen->id) }}" class="p-1.5 text-stone hover:text-jet" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('regimens.edit', $regimen->id) }}" class="p-1.5 text-stone hover:text-jet" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('regimens.destroy', $regimen->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-stone hover:text-red-600" title="Eliminar" onclick="return confirm('¿Está seguro de eliminar este régimen?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-stoneLight">
                            No se encontraron regímenes.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $regimens->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
