<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Usuario;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

new #[Layout('components.layouts.app')] class extends Component {
    use WithPagination;

    public bool $mostrarModalReset = false;
    public int $usuarioIdReset = 0;
    public string $nuevaPassword = '';
    public string $nuevaPasswordConfirm = '';

    public function with(): array
    {
        return [
            'usuarios' => Usuario::orderBy('idUsuario', 'desc')->paginate(10),
        ];
    }

    /**
     * Activa o desactiva un usuario.
     */
    public function toggleEstado(int $id): void
    {
        $usuario = Usuario::findOrFail($id);

        if ($usuario->idUsuario === Auth::id()) {
            $this->dispatch('notify', message: 'No puedes desactivar tu propia cuenta.', type: 'error');
            return;
        }

        $usuario->estado = !$usuario->estado;
        $usuario->save();

        $accion = $usuario->estado ? 'Usuario activado' : 'Usuario desactivado';
        Bitacora::registrar("{$accion}: {$usuario->nombre} {$usuario->apellido}", Auth::id());

        $this->dispatch('notify', message: "{$accion} correctamente.", type: 'success');
    }

    /**
     * Abre el modal para resetear la contraseña.
     */
    public function abrirModalReset(int $id): void
    {
        $this->usuarioIdReset = $id;
        $this->nuevaPassword = '';
        $this->nuevaPasswordConfirm = '';
        $this->mostrarModalReset = true;
    }

    /**
     * Ejecuta el reseteo de contraseña.
     */
    public function ejecutarResetPassword(): void
    {
        $this->validate([
            'nuevaPassword' => ['required', 'string', 'min:8', 'same:nuevaPasswordConfirm'],
            'nuevaPasswordConfirm' => ['required'],
        ], [
            'nuevaPassword.required' => 'La nueva contraseña es obligatoria.',
            'nuevaPassword.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'nuevaPassword.same' => 'Las contraseñas no coinciden.',
        ]);

        $usuario = Usuario::findOrFail($this->usuarioIdReset);
        $usuario->password = Hash::make($this->nuevaPassword);
        $usuario->save();

        Bitacora::registrar("Reset de contraseña para: {$usuario->nombre} {$usuario->apellido}", Auth::id());

        $this->mostrarModalReset = false;
        $this->dispatch('notify', message: "Contraseña actualizada para {$usuario->nombre} {$usuario->apellido}.", type: 'success');
    }

    /**
     * Cancela el modal.
     */
    public function cerrarModalReset(): void
    {
        $this->mostrarModalReset = false;
        $this->nuevaPassword = '';
        $this->nuevaPasswordConfirm = '';
    }
}; ?>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-100">Gestión de Usuarios</h2>
            <p class="text-sm text-gray-400 mt-1">Administración de personal, roles y accesos del sistema ERP.</p>
        </div>
        <a href="{{ route('usuarios.crear') }}" wire:navigate
           class="inline-flex items-center gap-2 px-4 py-2 bg-cyan-400 text-black text-sm font-bold rounded-lg neon-glow hover:bg-cyan-300 transition-all duration-200">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Crear Usuario
        </a>
    </div>

    <!-- Tabla -->
    <div class="bg-[#202022] border border-gray-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm min-w-[900px]">
                <thead class="bg-[#161618] text-gray-400 text-xs uppercase font-semibold border-b border-gray-800">
                    <tr>
                        <th class="px-5 py-3">Nombre</th>
                        <th class="px-5 py-3">Email</th>
                        <th class="px-5 py-3">Teléfono</th>
                        <th class="px-5 py-3">Rol</th>
                        <th class="px-5 py-3">Estado</th>
                        <th class="px-5 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800 text-gray-300">
                    @forelse ($usuarios as $usuario)
                        <tr class="hover:bg-[#262629] transition-colors">
                            <td class="px-5 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-cyan-400/10 text-cyan-400 flex items-center justify-center font-bold text-xs border border-cyan-400/30">
                                        {{ strtoupper(substr($usuario->nombre, 0, 1) . substr($usuario->apellido, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-100">{{ $usuario->nombre }} {{ $usuario->apellido }}</div>
                                        <div class="text-xs text-gray-500">ID: {{ $usuario->idUsuario }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap text-gray-100">{{ $usuario->email }}</td>
                            <td class="px-5 py-3 whitespace-nowrap">{{ $usuario->telefono ?? '—' }}</td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <div class="flex flex-wrap gap-1">
                                    @if($usuario->tipoSupervisor)
                                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-semibold bg-cyan-400/10 text-cyan-400 border border-cyan-400/20 uppercase tracking-wider">Supervisor</span>
                                    @endif
                                    @if($usuario->tipoAssesor)
                                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-400/10 text-emerald-400 border border-emerald-400/20 uppercase tracking-wider">Asesor</span>
                                    @endif
                                    @if($usuario->tipoTecnico)
                                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-semibold bg-purple-400/10 text-purple-400 border border-purple-400/20 uppercase tracking-wider">Técnico</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                @if($usuario->estado)
                                    <span class="inline-flex px-2 py-1 rounded text-xs font-semibold bg-emerald-400/10 text-emerald-400 border border-emerald-400/20">Activo</span>
                                @else
                                    <span class="inline-flex px-2 py-1 rounded text-xs font-semibold bg-red-400/10 text-red-400 border border-red-400/20">Inactivo</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="toggleEstado({{ $usuario->idUsuario }})"
                                            class="px-3 py-1.5 text-xs font-medium rounded border transition-all duration-200
                                            {{ $usuario->estado ? 'border-red-400/30 text-red-400 hover:bg-red-400/10' : 'border-emerald-400/30 text-emerald-400 hover:bg-emerald-400/10' }}">
                                        {{ $usuario->estado ? 'Suspender' : 'Activar' }}
                                    </button>
                                    <button wire:click="abrirModalReset({{ $usuario->idUsuario }})"
                                            class="px-3 py-1.5 text-xs font-medium rounded border border-cyan-400/30 text-cyan-400 hover:bg-cyan-400/10 transition-all duration-200">
                                        Resetear Clave
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <svg class="w-8 h-8 text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493A15.87 15.87 0 0112 19.5c-1.89 0-3.68-.549-5.21-1.487a4.125 4.125 0 00-7.533 2.493A9.337 9.337 0 003.375 19.5M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zM3.75 12a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                    </svg>
                                    No hay usuarios registrados.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($usuarios->hasPages())
            <div class="px-5 py-4 border-t border-gray-800 bg-[#161618]">
                {{ $usuarios->links(data: ['scrollTo' => false]) }}
            </div>
        @endif
    </div>

    <!-- Modal Reset Password -->
    @if($mostrarModalReset)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
             wire:click.self="cerrarModalReset">
            <div class="bg-[#202022] border border-gray-800 rounded-xl p-6 w-full max-w-md shadow-2xl mx-4">
                <h3 class="text-lg font-bold text-gray-100 mb-1">Resetear Contraseña</h3>
                <p class="text-sm text-gray-400 mb-4">Ingrese la nueva contraseña temporal para el usuario.</p>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-1">Nueva Contraseña</label>
                        <input wire:model="nuevaPassword" type="password"
                               class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                               placeholder="••••••••">
                        @error('nuevaPassword') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-1">Confirmar Contraseña</label>
                        <input wire:model="nuevaPasswordConfirm" type="password"
                               class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                               placeholder="••••••••">
                        @error('nuevaPasswordConfirm') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-6">
                    <button wire:click="cerrarModalReset"
                            class="flex-1 px-4 py-2 text-sm font-medium text-gray-300 bg-gray-800 border border-gray-700 rounded-lg hover:bg-gray-700 transition-all">
                        Cancelar
                    </button>
                    <button wire:click="ejecutarResetPassword"
                            class="flex-1 px-4 py-2 text-sm font-bold text-black bg-cyan-400 rounded-lg hover:bg-cyan-300 neon-glow transition-all">
                        Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Notificación Toast -->
    <div x-data="{ show: false, message: '', type: 'success' }"
         x-on:notify.window="show = true; message = $event.detail.message; type = $event.detail.type; setTimeout(() => show = false, 3000)"
         x-show="show"
         x-transition.opacity
         class="fixed bottom-6 right-6 z-50">
        <div :class="type === 'success' ? 'bg-emerald-400/10 border-emerald-400/30 text-emerald-400' : 'bg-red-400/10 border-red-400/30 text-red-400'"
             class="px-4 py-3 rounded-lg border backdrop-blur-sm text-sm font-medium shadow-lg flex items-center gap-2">
            <span x-text="message"></span>
        </div>
    </div>
</div>
