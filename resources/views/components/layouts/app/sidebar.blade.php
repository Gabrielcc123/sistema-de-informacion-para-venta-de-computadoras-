@auth
@php
$u = Auth::user();
$rolNombre = match(true) {
    $u->tipoSupervisor => 'Administrador',
    $u->tipoAssesor    => 'Vendedor',
    $u->tipoTecnico    => 'Técnico',
    default            => 'Usuario',
};
@endphp

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="bg-surface-container fixed left-0 top-0 h-full w-[260px] border-r border-outline-variant flex flex-col py-6 z-50 transform transition-transform duration-300 md:translate-x-0">

    <!-- Logo -->
    <div class="px-6 mb-8 flex items-center gap-3">
        <div class="w-10 h-10 rounded bg-primary/20 flex items-center justify-center border border-primary/30">
            <span class="material-symbols-outlined text-primary">memory</span>
        </div>
        <div>
            <h1 class="font-bold text-primary text-xl leading-tight">Iris Computer</h1>
            <p class="font-mono text-on-surface-variant text-[10px] uppercase tracking-widest">ERP System</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 flex flex-col gap-1 px-2 overflow-y-auto">

        {{-- Dashboard: todos los roles --}}
        <a href="{{ route('dashboard') }}" wire:navigate
           class="flex items-center gap-3 px-4 py-3 rounded transition-all duration-200
           {{ request()->routeIs('dashboard') ? 'text-primary bg-primary/10 border-r-2 border-primary scale-[0.98]' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-highest' }}">
            <span class="material-symbols-outlined {{ request()->routeIs('dashboard') ? 'font-variation-settings:FILL 1' : '' }}">dashboard</span>
            <span class="font-mono text-xs uppercase tracking-wider">Panel Principal</span>
        </a>

        {{-- Ventas: Supervisor + Vendedor --}}
        @if($u->tipoSupervisor || $u->tipoAssesor)
        <a href="{{ route('ventas.index') }}" wire:navigate
           class="flex items-center gap-3 px-4 py-3 rounded transition-all duration-200
           {{ request()->routeIs('ventas.*') ? 'text-primary bg-primary/10 border-r-2 border-primary scale-[0.98]' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-highest' }}">
            <span class="material-symbols-outlined {{ request()->routeIs('ventas.*') ? 'font-variation-settings:FILL 1' : '' }}">payments</span>
            <span class="font-mono text-xs uppercase tracking-wider">Ventas</span>
        </a>
        @endif

        {{-- Inventario: Supervisor + Vendedor --}}
        @if($u->tipoSupervisor || $u->tipoAssesor)
        <a href="#" wire:navigate
           class="flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:text-on-surface hover:bg-surface-container-highest rounded transition-all duration-200">
            <span class="material-symbols-outlined">inventory_2</span>
            <span class="font-mono text-xs uppercase tracking-wider">Inventario</span>
        </a>
        @endif

        {{-- Clientes: Supervisor + Vendedor --}}
        @if($u->tipoSupervisor || $u->tipoAssesor)
        <a href="#" wire:navigate
           class="flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:text-on-surface hover:bg-surface-container-highest rounded transition-all duration-200">
            <span class="material-symbols-outlined">people</span>
            <span class="font-mono text-xs uppercase tracking-wider">Clientes</span>
        </a>
        @endif

        {{-- Órdenes de Servicio: Supervisor + Técnico --}}
        @if($u->tipoSupervisor || $u->tipoTecnico)
        <a href="{{ route('ordenes.index') }}" wire:navigate
           class="flex items-center gap-3 px-4 py-3 rounded transition-all duration-200
           {{ request()->routeIs('ordenes.*') ? 'text-primary bg-primary/10 border-r-2 border-primary scale-[0.98]' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-highest' }}">
            <span class="material-symbols-outlined {{ request()->routeIs('ordenes.*') ? 'font-variation-settings:FILL 1' : '' }}">reset_wrench</span>
            <span class="font-mono text-xs uppercase tracking-wider">Órdenes de Servicio</span>
        </a>
        @endif

        {{-- Equipos: Supervisor + Técnico --}}
        @if($u->tipoSupervisor || $u->tipoTecnico)
        <a href="#" wire:navigate
           class="flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:text-on-surface hover:bg-surface-container-highest rounded transition-all duration-200">
            <span class="material-symbols-outlined">deployed_code</span>
            <span class="font-mono text-xs uppercase tracking-wider">Equipos</span>
        </a>
        @endif

        {{-- Bitácora: Solo Supervisor --}}
        @if($u->tipoSupervisor)
        <a href="{{ route('bitacora.index') }}" wire:navigate
           class="flex items-center gap-3 px-4 py-3 rounded transition-all duration-200
           {{ request()->routeIs('bitacora.*') ? 'text-primary bg-primary/10 border-r-2 border-primary scale-[0.98]' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-highest' }}">
            <span class="material-symbols-outlined {{ request()->routeIs('bitacora.*') ? 'font-variation-settings:FILL 1' : '' }}">receipt_long</span>
            <span class="font-mono text-xs uppercase tracking-wider">Bitácora</span>
        </a>
        @endif

        {{-- Usuarios: Solo Supervisor --}}
        @if($u->tipoSupervisor)
        <a href="#" wire:navigate
           class="flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:text-on-surface hover:bg-surface-container-highest rounded transition-all duration-200">
            <span class="material-symbols-outlined">admin_panel_settings</span>
            <span class="font-mono text-xs uppercase tracking-wider">Usuarios</span>
        </a>
        @endif

        {{-- Reportes: Solo Supervisor --}}
        @if($u->tipoSupervisor)
        <a href="#" wire:navigate
           class="flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:text-on-surface hover:bg-surface-container-highest rounded transition-all duration-200">
            <span class="material-symbols-outlined">bar_chart</span>
            <span class="font-mono text-xs uppercase tracking-wider">Reportes</span>
        </a>
        @endif
    </nav>

    <!-- Footer: User & Logout -->
    <div class="mt-auto px-2 pt-4 border-t border-outline-variant">
        <div class="px-4 py-3 flex items-center gap-3 mb-2">
            <div class="w-9 h-9 rounded-full bg-primary-container/30 text-primary flex items-center justify-center text-xs font-bold border border-primary/30">
                {{ strtoupper(substr($u->nombre, 0, 1) . substr($u->apellido, 0, 1)) }}
            </div>
            <div class="overflow-hidden">
                <p class="text-sm font-medium text-on-surface truncate">{{ $u->nombre }} {{ $u->apellido }}</p>
                <p class="text-xs text-on-surface-variant truncate">{{ $rolNombre }} · {{ $u->email }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-on-surface-variant hover:text-on-surface hover:bg-surface-container-highest rounded transition-all duration-200">
                <span class="material-symbols-outlined">logout</span>
                <span class="font-mono text-xs uppercase tracking-wider">Cerrar Sesión</span>
            </button>
        </form>
    </div>
</aside>
@endauth
