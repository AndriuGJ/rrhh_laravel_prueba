<!-- Top bar solo móvil -->
<div class="lg:hidden sticky top-0 z-40 flex items-center justify-between h-14 px-4 bg-gradient-to-r from-brandBlack via-jet to-stone text-almondDark shadow-lg">
    <button type="button" id="sidebarOpen" class="p-2 rounded hover:bg-white/10" aria-label="Abrir menú">
        <i class="fas fa-bars"></i>
    </button>
    <div class="flex items-center gap-2">
        <img src="{{ \App\Support\Empresa::logoUrl() }}" alt="{{ \App\Support\Empresa::nombre() }}" class="h-8 w-8 rounded border-2 border-khaki object-contain bg-white">
        <span class="font-semibold text-sm">RRHH</span>
    </div>
    <div class="w-10"></div>
</div>

<!-- Sidebar -->
<aside id="appSidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-brandBlack via-jet to-stone text-almondDark flex flex-col -translate-x-full lg:translate-x-0 transition-transform duration-200 shadow-xl">
    <!-- Logo -->
    <div class="flex items-center gap-3 h-16 px-4 border-b border-white/10 shrink-0">
        <img src="{{ \App\Support\Empresa::logoUrl() }}" alt="{{ \App\Support\Empresa::nombre() }}" class="h-10 w-10 rounded-lg border-2 border-khaki object-contain bg-white">
        <div>
            <span class="font-semibold text-sm">RRHH</span>
            <p class="text-xs text-almond/70">{{ \App\Support\Empresa::nombreCorto() }}</p>
        </div>
        <button type="button" id="sidebarClose" class="ml-auto lg:hidden p-2 rounded hover:bg-white/10" aria-label="Cerrar menú">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Menú -->
    <nav class="flex-1 overflow-y-auto p-3 space-y-1">
        @include('layouts._nav-items', [
            'linkClass' => 'flex items-center w-full text-left px-4 py-2.5 rounded text-sm font-medium hover:bg-white/10 transition',
            'activeClass' => 'bg-white/15 text-khakiLight',
            'layout' => 'sidebar',
        ])
    </nav>

    <!-- Usuario -->
    @if(Auth::check())
        <div class="border-t border-white/10 p-4 shrink-0">
            <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
            <p class="text-xs text-almond/70">{{ Auth::user()->rol->nombre ?? 'Usuario' }}</p>
            <form action="{{ route('logout') }}" method="POST" class="mt-3">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded bg-white/10 hover:bg-white/20 text-sm font-medium transition">
                    <i class="fas fa-sign-out-alt"></i>Cerrar sesión
                </button>
            </form>
        </div>
    @endif
</aside>

<!-- Overlay móvil -->
<div id="sidebarOverlay" class="fixed inset-0 z-40 bg-black/50 hidden lg:hidden"></div>

<script>
    (function () {
        const sidebar = document.getElementById('appSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const openBtn = document.getElementById('sidebarOpen');
        const closeBtn = document.getElementById('sidebarClose');

        const open = () => {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        };
        const close = () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        };

        openBtn && openBtn.addEventListener('click', open);
        closeBtn && closeBtn.addEventListener('click', close);
        overlay && overlay.addEventListener('click', close);
    })();
</script>
