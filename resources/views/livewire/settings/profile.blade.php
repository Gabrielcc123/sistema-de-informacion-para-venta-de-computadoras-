<?php

use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout; // <-- 1. Importamos el Atributo Layout

// 2. Forzamos la ruta correcta del layout
new #[Layout('components.layouts.app')] class extends Component {
    public string $nombre = '';
    public string $apellido = '';
    public string $email = '';

    public function mount(): void
    {
        $this->nombre = Auth::user()->nombre;
        $this->apellido = Auth::user()->apellido;
        $this->email = Auth::user()->email;
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(Usuario::class)->ignore($user->id),
            ],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->nombre);
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout heading="Información del Perfil" subheading="Actualice la información del perfil y la dirección de correo electrónico de su cuenta.">
        <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
            
            <flux:input
                wire:model="nombre"
                id="nombre"
                label="Nombre"
                type="text"
                name="nombre"
                required
                autofocus
                autocomplete="given-name"
            />

            <flux:input
                wire:model="apellido"
                id="apellido"
                label="Apellido"
                type="text"
                name="apellido"
                required
                autocomplete="family-name"
            />

            <flux:input
                wire:model="email"
                id="email"
                label="Correo electrónico"
                type="email"
                name="email"
                required
                autocomplete="email"
            />

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">Guardar</flux:button>
                </div>

                <x-action-message class="me-3 text-emerald-500" on="profile-updated">
                    ¡Guardado exitosamente!
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>