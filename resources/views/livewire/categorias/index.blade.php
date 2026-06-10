<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Categoria;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

new #[Layout('components.layouts.app')] class extends Component {
    use WithPagination;

    public string $search = '';
    public bool $mostrarModal = false;
    public bool $modoEdicion = false;
    public ?int $idCategoria = null;

    public string $nombre = '';
    public string $descripcion = '';

    public function with(): array
    {
        return [
            'categorias' => Categoria::where(function ($q) {
                    $q->where('nombre', 'like', "%{$this->search}%")
                      ->orWhere('descripcion', 'like', "%{$this->search}%");
                })
                ->withCount('productosServicios')
                ->orderBy('idCategoria', 'desc')
                ->paginate(10),
        ];
    }

    public function abrirModal(?int $id = null): void
    {
        $this->reset(['nombre', 'descripcion']);
        $this->idCategoria = $id;
        $this->modoEdicion = !is_null($id);

        if ($this->modoEdicion) {
            $categoria = Categoria::findOrFail($id);
            $this->nombre = $categoria->nombre;
            $this->descripcion = $categoria->descripcion ?? '';
        }

        $this->mostrarModal = true;
    }

    public function cerrarModal(): void
    {
        $this->mostrarModal = false;
        $this->reset(['nombre', 'descripcion', 'idCategoria', 'modoEdicion']);
    }

    public function guardar(): void
    {
        $rules = [
            'nombre'      => ['required', 'string', 'max:100', Rule::unique('categoria', 'nombre')->ignore($this->idCategoria, 'idCategoria')],
            'descripcion' => ['nullable', 'string', 'max:500'],
        ];

        $this->validate($rules, [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.unique'   => 'Ya existe una categoría con ese nombre.',
        ]);

        if ($this->modoEdicion) {
            $categoria = Categoria::findOrFail($this->idCategoria);
            $categoria->update([
                'nombre'      => $this->nombre,
                'descripcion' => $this->descripcion ?: null,
            ]);

            Bitacora::registrar("Categoría editada: {$categoria->nombre}", Auth::id());
            $this->dispatch('notify', message: 'Categoría actualizada correctamente.', type: 'success');
        } else {
            $categoria = Categoria::create([
                'nombre'      => $this->nombre,
                'descripcion' => $this->descripcion ?: null,
            ]);

            Bitacora::registrar("Categoría creada: {$categoria->nombre}", Auth::id());
            $this->dispatch('notify', message: 'Categoría registrada correctamente.', type: 'success');
        }

        $this->cerrarModal();
    }

    public function eliminar(int $id): void
    {
        $categoria = Categoria::findOrFail($id);

        if ($categoria->productosServicios()->exists()) {
            $this->dispatch('notify', message: 'No se puede eliminar la categoría porque tiene productos/servicios asociados.', type: 'error');
            return;
        }

        $categoria->delete();
        Bitacora::registrar("Categoría eliminada: {$categoria->nombre}", Auth::id());
        $this->dispatch('notify', message: 'Categoría eliminada correctamente.', type: 'success');
    }
};
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-100">Gestión de Categorías</h2>
            <p class="text-sm text-gray-400 mt-1">Clasificaciones de productos y servicios del sistema ERP.</p>
        </div>
        <button wire:click="abrirModal(null)"
                class="inline-flex items-center gap-2 px-4 py-2 bg-cyan-400 text-black text-sm font-bold rounded-lg neon-glow hover:bg-cyan-300 transition-all duration-200">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Nueva Categoría
        </button>
    </div>

    <div class="bg-[#202022] border border-gray-800 rounded-xl px-5 py-4">
        <div class="relative max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar categoría..."
                   class="block w-full pl-10 pr-3 py-2 border border-gray-700 rounded-lg bg-[#161618] text-gray-100 placeholder-gray-600 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 sm:text-sm transition-all">
        </div>
    </div>

    <div class="bg-[#202022] border border-gray-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm min-w-[600px]">
                <thead class="bg-[#161618] text-gray-400 text-xs uppercase font-semibold border-b border-gray-800">
                    <tr>
                        <th class="px-5 py-3">Categoría</th>
                        <th class="px-5 py-3">Descripción</th>
                        <th class="px-5 py-3 text-center">Productos</th>
                        <th class="px-5 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800 text-gray-300">
                    @forelse ($categorias as $categoria)
                        <tr class="hover:bg-[#262629] transition-colors">
                            <td class="px-5 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-cyan-400/10 text-cyan-400 flex items-center justify-center font-bold text-xs border border-cyan-400/30">
                                        {{ strtoupper(substr($categoria->nombre, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-100">{{ $categoria->nombre }}</div>
                                        <div class="text-xs text-gray-500">ID: {{ $categoria->idCategoria }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-400 max-w-xs truncate">{{ $categoria->descripcion ?? '—' }}</td>
                            <td class="px-5 py-3 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-400/10 text-cyan-400 border border-cyan-400/30">
                                    {{ $categoria->productos_servicios_count }}
                                </span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="abrirModal({{ $categoria->idCategoria }})"
                                            class="px-3 py-1.5 text-xs font-medium rounded border border-cyan-400/30 text-cyan-400 hover:bg-cyan-400/10 transition-all duration-200">
                                        Editar
                                    </button>
                                    <button wire:click="eliminar({{ $categoria->idCategoria }})"
                                            wire:confirm="¿Está seguro de eliminar la categoría {{ $categoria->nombre }}?"
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
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.593l1.598 1.597a2.25 2.25 0 011.593.659l6.188 6.187a2.25 2.25 0 002.832.141l3.392-2.832a2.25 2.25 0 00.141-2.832l-6.187-6.188a2.25 2.25 0 00-.659-1.593l-1.597-1.598A2.25 2.25 0 009.568 3z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                                    </svg>
                                    No hay categorías registradas.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($categorias->hasPages())
            <div class="px-5 py-4 border-t border-gray-800 bg-[#161618]">
                {{ $categorias->links(data: ['scrollTo' => false]) }}
            </div>
        @endif
    </div>

    @if($mostrarModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
             wire:click.self="cerrarModal">
            <div class="bg-[#202022] border border-gray-800 rounded-xl p-6 w-full max-w-lg shadow-2xl mx-4">
                <h3 class="text-lg font-bold text-gray-100 mb-1">
                    {{ $modoEdicion ? 'Editar Categoría' : 'Nueva Categoría' }}
                </h3>
                <p class="text-sm text-gray-400 mb-5">Complete los datos de la categoría.</p>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-1">Nombre <span class="text-red-400">*</span></label>
                        <input wire:model="nombre" type="text"
                               class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                               placeholder="Ej: Componentes PC">
                        @error('nombre') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-1">Descripción</label>
                        <textarea wire:model="descripcion" rows="3"
                                  class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600 resize-none"
                                  placeholder="Descripción opcional de la categoría..."></textarea>
                        @error('descripcion') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-6">
                    <button wire:click="cerrarModal"
                            class="flex-1 px-4 py-2 text-sm font-medium text-gray-300 bg-gray-800 border border-gray-700 rounded-lg hover:bg-gray-700 transition-all">
                        Cancelar
                    </button>
                    <button wire:click="guardar"
                            class="flex-1 px-4 py-2 text-sm font-bold text-black bg-cyan-400 rounded-lg hover:bg-cyan-300 neon-glow transition-all">
                        {{ $modoEdicion ? 'Guardar Cambios' : 'Registrar Categoría' }}
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