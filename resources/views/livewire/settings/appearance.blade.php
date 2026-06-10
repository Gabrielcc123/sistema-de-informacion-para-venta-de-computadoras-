<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout; // <-- 1. Importamos el layout

// 2. Forzamos el uso de tu plantilla principal
new #[Layout('components.layouts.app')] class extends Component {
    // No se requiere lógica de servidor aquí.
    // Flux gestiona el tema oscuro/claro automáticamente con Alpine.js
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout heading="Apariencia" subheading="Actualiza el tema visual del sistema para adaptarlo a tu preferencia.">
        <div class="mt-6 max-w-xl">
            
            <flux:radio.group x-data variant="segmented" x-model="$flux.appearance" label="Tema de la interfaz">
                <flux:radio value="light" icon="sun">Claro</flux:radio>
                <flux:radio value="dark" icon="moon">Oscuro</flux:radio>
                <flux:radio value="system" icon="computer-desktop">Sistema</flux:radio>
            </flux:radio.group>
            
        </div>
    </x-settings.layout>
</section>