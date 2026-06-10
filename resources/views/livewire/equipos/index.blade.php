<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Equipo;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;

new #[Layout('components.layouts.app')] class extends Component {
    use WithPagination;

    public string $search = '';
    public bool $mostrarModal = false;
    public bool $modoEdicion = false;
    public ?int $idEquipo = null;

    public string $marca = '';
    public string $modelo = '';
    public string $numeroSerie = '';
    public string $descripcion = '';
    public string $gama = 'Media';
    public string $estado = 'Recibido';

    public function with(): array
    {
        return [
            'equipos' => Equipo::when($this->search, function ($q) {
                    $q->where('marca', 'like', "%{$this->search}%")
                      ->orWhere('modelo', 'like', "%{$this->search}%")
                      ->orWhere('numeroSerie', 'like', "%{$this->search}%")
                      ->orWhere('descripcion', 'like', "%{$this->search}%");
                })
                ->orderBy('idEquipo', 'desc')
                ->paginate(10),
        ];
    }

    public function abrirModal(?int $id = null): void
    {
        $this->reset(['marca', 'modelo', 'numeroSerie', 'descripcion', 'gama', 'estado']);
        $this->idEquipo = $id;
        $this->modoEdicion = !is_null($id);

        if ($this->modoEdicion) {
            $equipo = Equipo::findOrFail($id);
            $this->marca = $equipo->marca ?? '';
            $this->modelo = $equipo->modelo ?? '';
            $this->numeroSerie = $equipo->numeroSerie ?? '';
            $this->descripcion = $equipo->descripcion ?? '';
            $this->gama = $equipo->gama ?? 'Media';
            $this->estado = $equipo->estado ?? 'Recibido';
        } else {
            $this->gama = 'Media';
            $this->estado = 'Recibido';
        }

        $this->mostrarModal = true;
    }

    public function cerrarModal(): void
    {
        $this->mostrarModal = false;
        $this->reset(['marca', 'modelo', 'numeroSerie', 'descripcion', 'gama', 'estado', 'idEquipo', 'modoEdicion']);
    }

    public function guardar(): void
    {
        $rules = [
            'marca'        => ['nullable', 'string', 'max:100'],
            'modelo'       => ['nullable', 'string', 'max:100'],
            'numeroSerie'  => ['nullable', 'string', 'max:100'],
            'descripcion'  => ['nullable', 'string', 'max:500'],
            'gama'         => ['required', 'in:Básica,Media,Alta'],
            'estado'       => ['required', 'in:Recibido,En Diagnóstico,En Reparación,Listo,Entregado'],
        ];

        $this->validate($rules, [
            'gama.required'   => 'La gama es obligatoria.',
            'estado.required' => 'El estado es obligatorio.',
        ]);

        if ($this->modoEdicion) {
            $equipo = Equipo::findOrFail($this->idEquipo);
            $equipo->update([
                'marca'       => $this->marca ?: null,
                'modelo'      => $this->modelo ?: null,
                'numeroSerie' => $this->numeroSerie ?: null,
                'descripcion' => $this->descripcion ?: null,
                'gama'        => $this->gama,
                'estado'      => $this->estado,
            ]);

            Bitacora::registrar("Equipo editado: {$equipo->marca} {$equipo->modelo} (ID: {$equipo->idEquipo})", Auth::id());
            $this->dispatch('notify', message: 'Equipo actualizado correctamente.', type: 'success');
        } else {
            $equipo = Equipo::create([
                'marca'       => $this->marca ?: null,
                'modelo'      => $this->modelo ?: null,
                'numeroSerie' => $this->numeroSerie ?: null,
                'descripcion' => $this->descripcion ?: null,
                'gama'        => $this->gama,
                'estado'      => $this->estado,
            ]);

            Bitacora::registrar("Equipo registrado: {$equipo->marca} {$equipo->modelo} (ID: {$equipo->idEquipo})", Auth::id());
            $this->dispatch('notify', message: 'Equipo registrado correctamente.', type: 'success');
        }

        $this->cerrarModal();
    }

    public function eliminar(int $id): void
    {
        $equipo = Equipo::findOrFail($id);

        if ($equipo->ordenes()->exists()) {
            $this->dispatch('notify', message: 'No se puede eliminar el equipo porque tiene órdenes de servicio asociadas.', type: 'error');
            return;
        }

        $equipo->delete();
        Bitacora::registrar("Equipo eliminado: ID {$id}", Auth::id());
        $this->dispatch('notify', message: 'Equipo eliminado correctamente.', type: 'success');
    }
};
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-100">Gestión de Equipos</h2>
            <p class="text-sm text-gray-400 mt-1">Registro y seguimiento de equipos para soporte técnico.</p>
        </div>
        <button wire:click="abrirModal(null)"
                class="inline-flex items-center gap-2 px-4 py-2 bg-cyan-400 text-black text-sm font-bold rounded-lg neon-glow hover:bg-cyan-300 transition-all duration-200">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Registrar Equipo
        </button>
    </div>

    <div class="bg-[#202022] border border-gray-800 rounded-xl px-5 py-4">
        <div class="relative max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar equipo..."
                   class="block w-full pl-10 pr-3 py-2 border border-gray-700 rounded-lg bg-[#161618] text-gray-100 placeholder-gray-600 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 sm:text-sm transition-all">
        </div>
    </div>

    <div class="bg-[#202022] border border-gray-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm min-w-[900px]">
                <thead class="bg-[#161618] text-gray-400 text-xs uppercase font-semibold border-b border-gray-800">
                    <tr>
                        <th class="px-5 py-3">ID</th>
                        <th class="px-5 py-3">Marca / Modelo</th>
                        <th class="px-5 py-3">Nro. Serie</th>
                        <th class="px-5 py-3">Gama</th>
                        <th class="px-5 py-3">Descripción</th>
                        <th class="px-5 py-3">Estado</th>
                        <th class="px-5 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800 text-gray-300">
                    @forelse ($equipos as $equipo)
                        <tr class="hover:bg-[#262629] transition-colors">
                            <td class="px-5 py-3 font-mono text-xs text-gray-500">#{{ $equipo->idEquipo }}</td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <div class="font-medium text-gray-100">{{ $equipo->marca ?? '—' }} {{ $equipo->modelo ?? '' }}</div>
                            </td>
                            <td class="px-5 py-3 font-mono text-xs">{{ $equipo->numeroSerie ?? '—' }}</td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                @php
                                    $gamaColors = ['Básica' => 'text-gray-400 border-gray-400/20 bg-gray-400/10', 'Media' => 'text-cyan-400 border-cyan-400/20 bg-cyan-400/10', 'Alta' => 'text-amber-400 border-amber-400/20 bg-amber-400/10'];
                                @endphp
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold border {{ $gamaColors[$equipo->gama] ?? 'text-gray-400 border-gray-400/20 bg-gray-400/10' }}">
                                    {{ $equipo->gama }}
                                </span>
                            </td>
                            <td class="px-5 py-3 max-w-xs truncate text-sm">{{ $equipo->descripcion ?? '—' }}</td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                @php
                                    $estadoColors = [
                                        'Recibido' => 'text-blue-400 border-blue-400/20 bg-blue-400/10',
                                        'En Diagnóstico' => 'text-amber-400 border-amber-400/20 bg-amber-400/10',
                                        'En Reparación' => 'text-purple-400 border-purple-400/20 bg-purple-400/10',
                                        'Listo' => 'text-emerald-400 border-emerald-400/20 bg-emerald-400/10',
                                        'Entregado' => 'text-gray-400 border-gray-400/20 bg-gray-400/10',
                                    ];
                                @endphp
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold border {{ $estadoColors[$equipo->estado] ?? 'text-gray-400 border-gray-400/20 bg-gray-400/10' }}">
                                    {{ $equipo->estado }}
                                </span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="abrirModal({{ $equipo->idEquipo }})"
                                            class="px-3 py-1.5 text-xs font-medium rounded border border-cyan-400/30 text-cyan-400 hover:bg-cyan-400/10 transition-all duration-200">
                                        Editar
                                    </button>
                                    <button wire:click="eliminar({{ $equipo->idEquipo }})"
                                            wire:confirm="¿Está seguro de eliminar este equipo?"
                                            class="px-3 py-1.5 text-xs font-medium rounded border border-red-400/30 text-red-400 hover:bg-red-400/10 transition-all duration-200">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <svg class="w-8 h-8 text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.032 4.032m-1.745 1.437l.102.085" />
                                    </svg>
                                    No hay equipos registrados.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($equipos->hasPages())
            <div class="px-5 py-4 border-t border-gray-800 bg-[#161618]">
                {{ $equipos->links(data: ['scrollTo' => false]) }}
            </div>
        @endif
    </div>

    @if($mostrarModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
             wire:click.self="cerrarModal">
            <div class="bg-[#202022] border border-gray-800 rounded-xl p-6 w-full max-w-lg shadow-2xl mx-4">
                <h3 class="text-lg font-bold text-gray-100 mb-1">
                    {{ $modoEdicion ? 'Editar Equipo' : 'Registrar Equipo' }}
                </h3>
                <p class="text-sm text-gray-400 mb-5">Complete los datos del equipo.</p>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-1">Marca</label>
                            <input wire:model="marca" type="text"
                                   class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                                   placeholder="Ej: Dell">
                        </div>
                        <div>
                            <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-1">Modelo</label>
                            <input wire:model="modelo" type="text"
                                   class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                                   placeholder="Ej: Inspiron 15">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-1">Número de Serie</label>
                            <input wire:model="numeroSerie" type="text"
                                   class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                                   placeholder="Ej: SN-ABC123">
                        </div>
                        <div>
                            <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-1">Gama <span class="text-red-400">*</span></label>
                            <select wire:model="gama"
                                    class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all">
                                <option value="Básica">Básica</option>
                                <option value="Media">Media</option>
                                <option value="Alta">Alta</option>
                            </select>
                            @error('gama') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-1">Descripción del problema</label>
                        <textarea wire:model="descripcion" rows="3"
                                  class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600 resize-none"
                                  placeholder="Describa el problema reportado..."></textarea>
                    </div>

                    @if($modoEdicion)
                        <div>
                            <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-1">Estado <span class="text-red-400">*</span></label>
                            <select wire:model="estado"
                                    class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all">
                                <option value="Recibido">Recibido</option>
                                <option value="En Diagnóstico">En Diagnóstico</option>
                                <option value="En Reparación">En Reparación</option>
                                <option value="Listo">Listo</option>
                                <option value="Entregado">Entregado</option>
                            </select>
                            @error('estado') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-3 mt-6">
                    <button wire:click="cerrarModal"
                            class="flex-1 px-4 py-2 text-sm font-medium text-gray-300 bg-gray-800 border border-gray-700 rounded-lg hover:bg-gray-700 transition-all">
                        Cancelar
                    </button>
                    <button wire:click="guardar"
                            class="flex-1 px-4 py-2 text-sm font-bold text-black bg-cyan-400 rounded-lg hover:bg-cyan-300 neon-glow transition-all">
                        {{ $modoEdicion ? 'Guardar Cambios' : 'Registrar Equipo' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

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