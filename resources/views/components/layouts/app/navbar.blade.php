@auth
<header class="bg-surface-container border-b border-outline-variant flex justify-between items-center h-16 px-6 sticky top-0 z-30">

    <!-- Left: Mobile toggle & Search -->
    <div class="flex items-center flex-1 gap-4">
        <button @click="sidebarOpen = true" class="md:hidden text-on-surface-variant hover:text-primary transition-colors duration-200">
            <span class="material-symbols-outlined">menu</span>
        </button>

        <div class="flex-1 max-w-md relative group hidden sm:block">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
            <input type="text" placeholder="Buscar en el sistema..."
                   class="w-full bg-surface-dim border border-outline-variant text-on-surface font-sans rounded pl-10 pr-3 py-1.5 focus:border-primary focus:ring-1 focus:ring-primary/50 focus:outline-none transition-all placeholder:text-on-surface-variant/50 text-sm" />
        </div>
    </div>

    <!-- Right: Actions & Profile -->
    <div class="flex items-center gap-4">
        <button class="text-on-surface-variant hover:text-primary transition-colors duration-200 relative group">
            <span class="material-symbols-outlined">notifications</span>
            <span class="absolute top-0 right-0 w-2 h-2 bg-primary rounded-full group-hover:shadow-[0_0_8px_rgba(34,211,238,0.8)]"></span>
        </button>

        <button class="text-on-surface-variant hover:text-primary transition-colors duration-200 hidden sm:block">
            <span class="material-symbols-outlined">help</span>
        </button>

        <div class="h-6 w-px bg-outline-variant mx-1 hidden sm:block"></div>

        <a href="{{ route('settings.profile') }}" wire:navigate class="flex items-center gap-2 text-on-surface-variant hover:text-primary transition-colors duration-200">
            <span class="material-symbols-outlined text-[28px]">account_circle</span>
        </a>
    </div>
</header>
@endauth
