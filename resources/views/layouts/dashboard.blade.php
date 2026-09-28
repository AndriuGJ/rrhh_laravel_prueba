@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php($tipoNavegacion = \App\Models\Configuration::get('tipo_navegacion', 'header'))

    @if($tipoNavegacion === 'sidebar')
        @include('layouts._sidebar')
    @else
        @include('layouts._header')
    @endif

    <div class="flex-1 flex flex-col {{ $tipoNavegacion === 'sidebar' ? 'lg:ml-64' : '' }}">
        <main class="w-full max-w-7xl mx-auto px-4 py-8 flex-1">
            @yield('main-content')
        </main>

        @include('layouts._footer')
    </div>
@endsection
