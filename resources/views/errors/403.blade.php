@extends('layouts.dashboard')

@section('title', 'Acceso Denegado')

@section('main-content')
    <div class="flex flex-col items-center justify-center min-h-[60vh] text-center">
        <div class="text-9xl font-bold text-red-500 mb-4">403</div>
        <h1 class="text-3xl font-bold text-brandBlack mb-4">Acceso Denegado</h1>
        <p class="text-lg text-stone mb-8">No tienes permiso para acceder a esta sección del sistema.</p>
        <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-jet text-almondDark font-medium hover:bg-jetLight rounded">
            <i class="fas fa-home mr-2"></i>Volver al Dashboard
        </a>
    </div>
@endsection