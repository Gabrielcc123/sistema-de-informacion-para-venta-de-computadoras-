<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component {
    //
};
?>

<div class="min-h-[60vh] flex items-center justify-center">
    <div class="flex flex-col items-center text-center space-y-6 max-w-lg px-6">
        <!-- Icono Grande -->
        <div class="relative">
            <svg class="w-20 h-20 text-cyan-400 drop-shadow-[0_0_10px_rgba(34,211,238,0.6)]" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.077-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.62a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42" />
            </svg>
        </div>

        <!-- Textos -->
        <div class="space-y-3">
            <h1 class="text-3xl font-bold text-gray-100 tracking-tight">Módulo en Desarrollo</h1>
            <p class="text-sm text-gray-400 leading-relaxed">
                Estamos trabajando en la programación de esta sección. Estará disponible en la próxima actualización del sistema.
            </p>
        </div>

        <!-- Botón Volver -->
        <a href="{{ route('dashboard') }}" wire:navigate
           class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-bold text-black bg-cyan-400 rounded-lg hover:bg-cyan-300 neon-glow transition-all duration-300">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Volver al Dashboard
        </a>
    </div>
</div>
