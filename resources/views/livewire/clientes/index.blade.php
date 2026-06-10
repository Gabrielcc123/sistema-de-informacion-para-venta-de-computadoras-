<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Cliente;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

new #[Layout('components.layouts.app')] class extends Component {
    use WithPagination;

    public string $search = '';
    public bool $mostrarModal = false;
    public bool $modoEdicion = false;
    public ?int $idCliente = null;

    public string $ci = '';
    public string $nombre = '';
    public string $apellido = '';
    public string $telefono = '';

    public function with(): array
    {
        return [
            'clientes' => Cliente::where(function ($q) {
                    $q->where('ci', 'like', "%{$this->search}%")
                      ->orWhere('nombre', 'like', "%{$this->search}%")
                      ->orWhere('apellido', 'like', "%{$this->search}%");
                })
                ->orderBy('idCliente', 'desc')
                ->paginate(10),
        ];
    }

    public function abrirModal(?int $id = null): void
    {
        $this->reset(['ci', 'nombre', 'apellido', 'telefono']);
        $this->idCliente = $id;
        $this->modoEdicion = !is_null($id);

        if ($this->modoEdicion) {
            $cliente = Cliente::findOrFail($id);
            $this->ci = $cliente->ci;
            $this->nombre = $cliente->nombre;
            $this->apellido = $cliente->apellido;
            $this->telefono = $cliente->telefono ?? '';
        }

        $this->mostrarModal = true;
    }

    public function cerrarModal(): void
    {
        $this->mostrarModal = false;
        $this->reset(['ci', 'nombre', 'apellido', 'telefono', 'idCliente', 'modoEdicion']);
    }

    public function guardar(): void
    {
        $rules = [
            'nombre'   => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'telefono' => ['nullable', 'string', 'max:30'],
        ];

        $rules['ci'] = ['required', 'string', 'max:30', Rule::unique('cliente', 'ci')->ignore($this->idCliente, 'idCliente')];

        $this->validate($rules, [
            'ci.required'       => 'El CI es obligatorio.',
            'ci.unique'         => 'Este CI ya está registrado.',
            'nombre.required'   => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
        ]);

        if ($this->modoEdicion) {
            $cliente = Cliente::findOrFail($this->idCliente);
            $cliente->update([
                'ci'        => $this->ci,
                'nombre'    => $this->nombre,
                'apellido'  => $this->apellido,
                'telefono'  => $this->telefono ?: null,
            ]);

            Bitacora::registrar("Cliente editado: {$cliente->nombre} {$cliente->apellido} (CI: {$cliente->ci})", Auth::id());
            $this->dispatch('notify', message: 'Cliente actualizado correctamente.', type: 'success');
        } else {
            $cliente = Cliente::create([
                'ci'        => $this->ci,
                'nombre'    => $this->nombre,
                'apellido'  => $this->apellido,
                'telefono'  => $this->telefono ?: null,
            ]);

            Bitacora::registrar("Cliente creado: {$cliente->nombre} {$cliente->apellido} (CI: {$cliente->ci})", Auth::id());
            $this->dispatch('notify', message: 'Cliente registrado correctamente.', type: 'success');
        }

        $this->cerrarModal();
    }

    public function eliminar(int $id): void
    {
        $cliente = Cliente::findOrFail($id);

        if ($cliente->notasVenta()->exists()) {
            $this->dispatch('notify', message: 'No se puede eliminar el cliente porque tiene notas de venta asociadas.', type: 'error');
            return;
        }

        $cliente->delete();
        Bitacora::registrar("Cliente eliminado: {$cliente->nombre} {$cliente->apellido} (CI: {$cliente->ci})", Auth::id());
        $this->dispatch('notify', message: 'Cliente eliminado correctamente.', type: 'success');
    }
};
?>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-100">Gestión de Clientes</h2>
            <p class="text-sm text-gray-400 mt-1">Cartera de clientes y contactos del sistema ERP.</p>
        </div>
        <button wire:click="abrirModal(null)"
                class="inline-flex items-center gap-2 px-4 py-2 bg-cyan-400 text-black text-sm font-bold rounded-lg neon-glow hover:bg-cyan-300 transition-all duration-200">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Nuevo Cliente
        </button>
    </div>

    <!-- Buscador -->
    <div class="bg-[#202022] border border-gray-800 rounded-xl px-5 py-4">
        <div class="relative max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por CI, nombre o apellido..."
                   class="block w-full pl-10 pr-3 py-2 border border-gray-700 rounded-lg bg-[#161618] text-gray-100 placeholder-gray-600 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 sm:text-sm transition-all">
        </div>
    </div>

    <!-- Tabla -->
    <div class="bg-[#202022] border border-gray-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm min-w-[700px]">
                <thead class="bg-[#161618] text-gray-400 text-xs uppercase font-semibold border-b border-gray-800">
                    <tr>
                        <th class="px-5 py-3">Cliente</th>
                        <th class="px-5 py-3">CI</th>
                        <th class="px-5 py-3">Teléfono</th>
                        <th class="px-5 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800 text-gray-300">
                    @forelse ($clientes as $cliente)
                        <tr class="hover:bg-[#262629] transition-colors">
                            <td class="px-5 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-cyan-400/10 text-cyan-400 flex items-center justify-center font-bold text-xs border border-cyan-400/30">
                                        {{ strtoupper(substr($cliente->nombre, 0, 1) . substr($cliente->apellido, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-100">{{ $cliente->nombre }} {{ $cliente->apellido }}</div>
                                        <div class="text-xs text-gray-500">ID: {{ $cliente->idCliente }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap text-gray-100 font-mono text-xs">{{ $cliente->ci }}</td>
                            <td class="px-5 py-3 whitespace-nowrap">{{ $cliente->telefono ?? '—' }}</td>
                            <td class="px-5 py-3 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="abrirModal({{ $cliente->idCliente }})"
                                            class="px-3 py-1.5 text-xs font-medium rounded border border-cyan-400/30 text-cyan-400 hover:bg-cyan-400/10 transition-all duration-200">
                                        Editar
                                    </button>
                                    <button wire:click="eliminar({{ $cliente->idCliente }})"
                                            wire:confirm="¿Está seguro de eliminar a {{ $cliente->nombre }} {{ $cliente->apellido }}?"
                                            class="px-3 py-1.5 text-xs font-medium rounded border border-red-400/30 text-red-400 hover:bg-red-400/10 transition-all duration-200">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <svg class="w-8 h-8 text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493A15.87 15.87 0 0112 19.5c-1.89 0-3.68-.549-5.21-1.487a4.125 4.125 0 00-7.533 2.493A9.337 9.337 0 003.375 19.5M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zM3.75 12a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                    </svg>
                                    No hay clientes registrados.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($clientes->hasPages())
            <div class="px-5 py-4 border-t border-gray-800 bg-[#161618]">
                {{ $clientes->links(data: ['scrollTo' => false]) }}
            </div>
        @endif
    </div>

    <!-- Modal Crear / Editar -->
    @if($mostrarModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
             wire:click.self="cerrarModal">
            <div class="bg-[#202022] border border-gray-800 rounded-xl p-6 w-full max-w-lg shadow-2xl mx-4">
                <h3 class="text-lg font-bold text-gray-100 mb-1">
                    {{ $modoEdicion ? 'Editar Cliente' : 'Nuevo Cliente' }}
                </h3>
                <p class="text-sm text-gray-400 mb-5">Complete los datos del cliente.</p>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-1">CI <span class="text-red-400">*</span></label>
                        <input wire:model="ci" type="text"
                               class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                               placeholder="Ej: 1234567">
                        @error('ci') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-1">Nombre <span class="text-red-400">*</span></label>
                            <input wire:model="nombre" type="text"
                                   class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                                   placeholder="Ej: Juan">
                            @error('nombre') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-1">Apellido <span class="text-red-400">*</span></label>
                            <input wire:model="apellido" type="text"
                                   class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                                   placeholder="Ej: Pérez">
                            @error('apellido') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-1">Teléfono</label>
                        <input wire:model="telefono" type="text"
                               class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                               placeholder="Ej: 71234567">
                        @error('telefono') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-6">
                    <button wire:click="cerrarModal"
                            class="flex-1 px-4 py-2 text-sm font-medium text-gray-300 bg-gray-800 border border-gray-700 rounded-lg hover:bg-gray-700 transition-all">
                        Cancelar
                    </button>
                    <button wire:click="guardar"
                            class="flex-1 px-4 py-2 text-sm font-bold text-black bg-cyan-400 rounded-lg hover:bg-cyan-300 neon-glow transition-all">
                        {{ $modoEdicion ? 'Guardar Cambios' : 'Registrar Cliente' }}
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
