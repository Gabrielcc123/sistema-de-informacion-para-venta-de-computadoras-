<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Usuario;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

new #[Layout('components.layouts.app')] class extends Component {
    public string $nombre = '';
    public string $apellido = '';
    public string $email = '';
    public string $telefono = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $tipoSupervisor = false;
    public bool $tipoAssesor = false;
    public bool $tipoTecnico = false;

    /**
     * Guarda un nuevo usuario en el sistema.
     */
    public function guardar(): void
    {
        $validated = $this->validate([
            'nombre'     => ['required', 'string', 'max:100'],
            'apellido'   => ['required', 'string', 'max:100'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:usuario,email'],
            'telefono'   => ['nullable', 'string', 'max:30'],
            'password'   => ['required', 'string', Password::defaults(), 'confirmed'],
            'tipoSupervisor' => ['boolean'],
            'tipoAssesor'    => ['boolean'],
            'tipoTecnico'    => ['boolean'],
        ], [
            'nombre.required'   => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'email.required'    => 'El correo electrónico es obligatorio.',
            'email.email'       => 'El correo electrónico no es válido.',
            'email.unique'      => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ]);

        // Regla de negocio: al menos un rol debe estar activo
        if (!$this->tipoSupervisor && !$this->tipoAssesor && !$this->tipoTecnico) {
            $this->addError('roles', 'Debe seleccionar al menos un rol para el usuario.');
            return;
        }

        $usuario = Usuario::create([
            'nombre'         => $this->nombre,
            'apellido'       => $this->apellido,
            'email'          => $this->email,
            'telefono'       => $this->telefono,
            'password'       => Hash::make($this->password),
            'estado'         => 1,
            'tipoSupervisor' => $this->tipoSupervisor,
            'tipoAssesor'    => $this->tipoAssesor,
            'tipoTecnico'    => $this->tipoTecnico,
        ]);

        Bitacora::registrar("Usuario creado: {$usuario->nombre} {$usuario->apellido} ({$usuario->email})", Auth::id());

        $this->redirectRoute('usuarios.index', navigate: true);
    }

    /**
     * Cancela la creación y vuelve al listado.
     */
    public function cancelar(): void
    {
        $this->redirectRoute('usuarios.index', navigate: true);
    }
}; ?>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-100">Crear Usuario</h2>
            <p class="text-sm text-gray-400 mt-1">Registra un nuevo empleado en el sistema ERP.</p>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-[#202022] border border-gray-800 rounded-xl p-6 max-w-2xl">
        <form wire:submit="guardar" class="space-y-6">

            <!-- Datos Personales -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Nombre <span class="text-red-400">*</span></label>
                    <input wire:model="nombre" type="text"
                           class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                           placeholder="Ej: Carlos">
                    @error('nombre') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Apellido <span class="text-red-400">*</span></label>
                    <input wire:model="apellido" type="text"
                           class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                           placeholder="Ej: Martínez">
                    @error('apellido') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Correo Electrónico <span class="text-red-400">*</span></label>
                    <input wire:model="email" type="email"
                           class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                           placeholder="ejemplo@iriscomputer.com">
                    @error('email') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Teléfono</label>
                    <input wire:model="telefono" type="text"
                           class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                           placeholder="Ej: 71234567">
                    @error('telefono') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-data="{ showPassword: false, showConfirm: false }">
                <div>
                    <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Contraseña <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <input wire:model="password" :type="showPassword ? 'text' : 'password'"
                               class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2.5 pr-10 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                               placeholder="••••••••">
                        <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-cyan-400 transition-colors">
                            <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.025 10.025 0 014.132-5.4m3.045-1.127A9.754 9.754 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.4M9.9 9.9l4.2 4.2m0-4.2l-4.2 4.2" />
                            </svg>
                        </button>
                    </div>
                    @error('password') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Confirmar Contraseña <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <input wire:model="password_confirmation" :type="showConfirm ? 'text' : 'password'"
                               class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2.5 pr-10 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                               placeholder="••••••••">
                        <button type="button" @click="showConfirm = !showConfirm"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-cyan-400 transition-colors">
                            <svg x-show="!showConfirm" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showConfirm" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.025 10.025 0 014.132-5.4m3.045-1.127A9.754 9.754 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.4M9.9 9.9l4.2 4.2m0-4.2l-4.2 4.2" />
                            </svg>
                        </button>
                    </div>
                    @error('password_confirmation') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Roles del Sistema -->
            <div>
                <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-3">Roles del Sistema <span class="text-red-400">*</span></label>
                <div class="flex flex-wrap gap-4">
                    <label class="flex items-center gap-3 px-4 py-3 bg-[#161618] border border-gray-800 rounded-lg cursor-pointer hover:border-cyan-400/50 transition-all select-none">
                        <input wire:model="tipoSupervisor" type="checkbox"
                               class="w-4 h-4 rounded bg-[#161618] border-gray-700 text-cyan-400 focus:ring-cyan-400/50 focus:ring-offset-0">
                        <div>
                            <div class="text-sm font-medium text-gray-100">Supervisor</div>
                            <div class="text-xs text-gray-500">Acceso total al sistema</div>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 px-4 py-3 bg-[#161618] border border-gray-800 rounded-lg cursor-pointer hover:border-cyan-400/50 transition-all select-none">
                        <input wire:model="tipoAssesor" type="checkbox"
                               class="w-4 h-4 rounded bg-[#161618] border-gray-700 text-cyan-400 focus:ring-cyan-400/50 focus:ring-offset-0">
                        <div>
                            <div class="text-sm font-medium text-gray-100">Asesor / Vendedor</div>
                            <div class="text-xs text-gray-500">Ventas, clientes e inventario</div>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 px-4 py-3 bg-[#161618] border border-gray-800 rounded-lg cursor-pointer hover:border-cyan-400/50 transition-all select-none">
                        <input wire:model="tipoTecnico" type="checkbox"
                               class="w-4 h-4 rounded bg-[#161618] border-gray-700 text-cyan-400 focus:ring-cyan-400/50 focus:ring-offset-0">
                        <div>
                            <div class="text-sm font-medium text-gray-100">Técnico</div>
                            <div class="text-xs text-gray-500">Órdenes de servicio y equipos</div>
                        </div>
                    </label>
                </div>
                @error('roles') <span class="text-red-400 text-xs mt-2">{{ $message }}</span> @enderror
            </div>

            <!-- Botones -->
            <div class="flex items-center gap-3 pt-2">
                <button type="button" wire:click="cancelar"
                        class="px-5 py-2.5 text-sm font-medium text-gray-300 bg-gray-800 border border-gray-700 rounded-lg hover:bg-gray-700 transition-all">
                    Cancelar
                </button>
                <button type="submit"
                        class="px-5 py-2.5 text-sm font-bold text-black bg-cyan-400 rounded-lg hover:bg-cyan-300 neon-glow transition-all">
                    Guardar Usuario
                </button>
            </div>
        </form>
    </div>
</div>
