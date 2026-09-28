<nav class="bg-gradient-to-r from-brandBlack via-jet to-stone text-almondDark shadow-lg">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center gap-3">
                <img src="{{ \App\Support\Empresa::logoUrl() }}" alt="{{ \App\Support\Empresa::nombre() }}" class="h-10 w-10 rounded-lg border-2 border-khaki object-contain bg-white">
                <div>
                    <span class="font-semibold text-sm">RRHH</span>
                    <p class="text-xs text-almond/70">{{ \App\Support\Empresa::nombreCorto() }}</p>
                </div>
            </div>

            <!-- Menú -->
            <div class="hidden md:flex space-x-1">
                @include('layouts._nav-items')
            </div>

            <!-- User Menu -->
            @if(Auth::check())
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-almond/70">{{ Auth::user()->rol->nombre ?? 'Usuario' }}</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="p-2 hover:bg-white/10 rounded" title="Cerrar sesión">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</nav>
