@php
    $linkClass = $linkClass ?? 'px-4 py-2 rounded text-sm font-medium hover:bg-white/10';
    $activeClass = $activeClass ?? 'bg-white/10 text-khaki';
    $layout = $layout ?? 'header';
    $configActiva = request()->routeIs('configurations.*', 'bonifications.*', 'regimens.*');
@endphp

@if(Auth::check())
    <a href="{{ route('dashboard') }}" class="{{ $linkClass }} {{ request()->routeIs('dashboard') ? $activeClass : '' }}">
        <i class="fas fa-chart-line mr-2"></i>Dashboard
    </a>
    <a href="{{ route('employees.index') }}" class="{{ $linkClass }} {{ request()->routeIs('employees.*') ? $activeClass : '' }}">
        <i class="fas fa-users mr-2"></i>Empleados
    </a>
    <a href="{{ route('payrolls.index') }}" class="{{ $linkClass }} {{ request()->routeIs('payrolls.*') ? $activeClass : '' }}">
        <i class="fas fa-file-invoice-dollar mr-2"></i>Planillas
    </a>
    <a href="{{ route('attendances.index') }}" class="{{ $linkClass }} {{ request()->routeIs('attendances.*') ? $activeClass : '' }}">
        <i class="fas fa-calendar-check mr-2"></i>Asistencia
    </a>
    <a href="{{ route('qr-employees.index') }}" class="{{ $linkClass }} {{ request()->routeIs('qr-employees.*') ? $activeClass : '' }}">
        <i class="fas fa-qrcode mr-2"></i>QR - Empleados
    </a>
    @if(Auth::user()->isAdmin())
        @if($layout === 'sidebar')
            <details {{ $configActiva ? 'open' : '' }}>
                <summary class="{{ $linkClass }} nav-toggle justify-between {{ $configActiva ? $activeClass : '' }}">
                    <span><i class="fas fa-cog mr-2"></i>Configuración</span>
                    <i class="fas fa-chevron-down text-[0.6rem] transition-transform nav-chevron"></i>
                </summary>
                <div class="mt-1 space-y-1">
                    <a href="{{ route('configurations.index') }}" class="{{ $linkClass }} pl-10 {{ request()->routeIs('configurations.*') ? $activeClass : '' }}">
                        General
                    </a>
                    <a href="{{ route('bonifications.index') }}" class="{{ $linkClass }} pl-10 {{ request()->routeIs('bonifications.*') ? $activeClass : '' }}">
                        Bonificaciones
                    </a>
                    <a href="{{ route('regimens.index') }}" class="{{ $linkClass }} pl-10 {{ request()->routeIs('regimens.*') ? $activeClass : '' }}">
                        Régimen
                    </a>
                </div>
            </details>
        @else
            <div class="relative group">
                <button type="button" class="{{ $linkClass }} flex items-center gap-2 {{ $configActiva ? $activeClass : '' }}">
                    <i class="fas fa-cog"></i>Configuración
                    <i class="fas fa-chevron-down text-[0.6rem]"></i>
                </button>
                <div class="absolute left-0 top-full z-50 hidden group-hover:block min-w-[210px] py-1 bg-jet border border-white/10 shadow-xl">
                    <a href="{{ route('configurations.index') }}" class="block px-4 py-2 text-sm text-almondDark hover:bg-white/10 {{ request()->routeIs('configurations.*') ? 'bg-white/10 text-khaki' : '' }}">
                        <i class="fas fa-sliders-h mr-2 w-4"></i>General
                    </a>
                    <a href="{{ route('bonifications.index') }}" class="block px-4 py-2 text-sm text-almondDark hover:bg-white/10 {{ request()->routeIs('bonifications.*') ? 'bg-white/10 text-khaki' : '' }}">
                        <i class="fas fa-gift mr-2 w-4"></i>Bonificaciones
                    </a>
                    <a href="{{ route('regimens.index') }}" class="block px-4 py-2 text-sm text-almondDark hover:bg-white/10 {{ request()->routeIs('regimens.*') ? 'bg-white/10 text-khaki' : '' }}">
                        <i class="fas fa-briefcase mr-2 w-4"></i>Régimen
                    </a>
                </div>
            </div>
        @endif
        <a href="{{ route('users.index') }}" class="{{ $linkClass }} {{ request()->routeIs('users.*') ? $activeClass : '' }}">
            <i class="fas fa-user-cog mr-2"></i>Usuarios
        </a>
    @endif
@endif
