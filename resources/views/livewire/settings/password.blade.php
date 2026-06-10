<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

// Forzamos el uso de tu plantilla principal con barra lateral
new #[Layout('components.layouts.app')] class extends Component {
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Actualiza la contraseña del usuario autenticado y lo registra en la bitácora.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ], [
                'current_password.current_password' => 'La contraseña actual no es correcta.',
                'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');
            throw $e;
        }

        // 1. Actualizar la contraseña en la base de datos
        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // 2. REGISTRO EN BITÁCORA: Guardamos la acción de este usuario
        \App\Models\Bitacora::registrar('Cambio de contraseña desde el perfil');

        // 3. Limpiar el formulario
        $this->reset('current_password', 'password', 'password_confirmation');

        // 4. Notificar a la interfaz del éxito
        $this->dispatch('password-updated');
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout heading="Actualizar Contraseña" subheading="Asegúrese de que su cuenta utilice una contraseña larga y aleatoria para mantenerse segura.">
        <form wire:submit="updatePassword" class="mt-6 space-y-6 max-w-xl">
            
            <flux:input
                wire:model="current_password"
                id="update_password_current_password"
                label="Contraseña actual"
                type="password"
                name="current_password"
                required
                autocomplete="current-password"
                viewable
            />
            
            <flux:input
                wire:model="password"
                id="update_password_password"
                label="Nueva contraseña"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                viewable
            />
            
            <flux:input
                wire:model="password_confirmation"
                id="update_password_password_confirmation"
                label="Confirmar nueva contraseña"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                viewable
            />

            <div class="flex items-center gap-4 pt-4">
                <flux:button variant="primary" type="submit">Guardar cambios</flux:button>

                <x-action-message class="me-3 text-emerald-500 font-medium" on="password-updated">
                    ¡Contraseña actualizada!
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>