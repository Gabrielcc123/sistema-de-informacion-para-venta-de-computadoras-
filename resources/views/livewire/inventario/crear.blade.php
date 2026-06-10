<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\ProductoServicio;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

new #[Layout('components.layouts.app')] class extends Component {
    public string $tipo = 'Producto';
    public ?int $idCategoria = null;
    public string $nombre = '';
    public string $precioUnitario = '';
    public string $garantia = '';
    public ?int $stock = null;
    public string $marca = '';
    public string $modelo = '';
    public string $numeroSerie = '';

    public function with(): array
    {
        return [
            'categorias' => Categoria::orderBy('nombre')->get(),
        ];
    }

    public function guardar(): void
    {
        $rules = [
            'tipo'           => ['required', 'in:Producto,Servicio'],
            'idCategoria'    => ['required', 'integer', 'exists:categoria,idCategoria'],
            'nombre'         => ['required', 'string', 'max:150'],
            'precioUnitario' => ['required', 'numeric', 'min:0'],
            'garantia'       => ['nullable', 'string', 'max:100'],
            'marca'          => ['nullable', 'string', 'max:100'],
            'modelo'         => ['nullable', 'string', 'max:100'],
            'numeroSerie'    => ['nullable', 'string', 'max:100'],
        ];

        if ($this->tipo === 'Producto') {
            $rules['stock'] = ['required', 'integer', 'min:0'];
        } else {
            $rules['stock'] = ['nullable', 'integer', 'min:0'];
        }

        $this->validate($rules, [
            'tipo.required'           => 'El tipo es obligatorio.',
            'idCategoria.required'    => 'Debe seleccionar una categoría.',
            'nombre.required'         => 'El nombre es obligatorio.',
            'precioUnitario.required' => 'El precio unitario es obligatorio.',
            'stock.required'          => 'El stock inicial es obligatorio para productos.',
            'stock.min'               => 'El stock no puede ser negativo.',
        ]);

        DB::transaction(function () {
            $ps = ProductoServicio::create([
                'idCategoria'    => $this->idCategoria,
                'nombre'         => $this->nombre,
                'precioUnitario' => $this->precioUnitario,
                'garantia'       => $this->garantia ?: null,
                'tipo'           => $this->tipo,
            ]);

            if ($this->tipo === 'Producto') {
                Producto::create([
                    'idProducto'   => $ps->idProductoServicio,
                    'stock'        => $this->stock ?? 0,
                    'marca'        => $this->marca ?: null,
                    'modelo'       => $this->modelo ?: null,
                    'numeroSerie'  => $this->numeroSerie ?: null,
                ]);
            }
        });

        Bitacora::registrar('Nuevo item en inventario: ' . $this->nombre, Auth::id());
        $this->dispatch('notify', message: 'Registro guardado correctamente.', type: 'success');

        $this->redirectRoute('inventario.index', navigate: true);
    }

    public function cancelar(): void
    {
        $this->redirectRoute('inventario.index', navigate: true);
    }
};
?>

<div class="space-y-6" x-data="{ tipo: @entangle('tipo') }">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-100">Nuevo Registro</h2>
            <p class="text-sm text-gray-400 mt-1">Agrega un producto o servicio al catálogo.</p>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-[#202022] border border-gray-800 rounded-xl p-6 max-w-3xl">
        <form wire:submit="guardar" class="space-y-6">

            <!-- Tipo -->
            <div>
                <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-3">Tipo <span class="text-red-400">*</span></label>
                <div class="flex flex-wrap gap-4">
                    <label class="flex items-center gap-3 px-4 py-3 bg-[#161618] border border-gray-800 rounded-lg cursor-pointer hover:border-cyan-400/50 transition-all select-none"
                           :class="tipo === 'Producto' ? 'border-cyan-400/50 ring-1 ring-cyan-400/30' : ''">
                        <input type="radio" wire:model.live="tipo" value="Producto"
                               class="w-4 h-4 bg-[#161618] border-gray-700 text-cyan-400 focus:ring-cyan-400/50 focus:ring-offset-0">
                        <div>
                            <div class="text-sm font-medium text-gray-100">Producto</div>
                            <div class="text-xs text-gray-500">Artículo físico con stock</div>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 px-4 py-3 bg-[#161618] border border-gray-800 rounded-lg cursor-pointer hover:border-cyan-400/50 transition-all select-none"
                           :class="tipo === 'Servicio' ? 'border-cyan-400/50 ring-1 ring-cyan-400/30' : ''">
                        <input type="radio" wire:model.live="tipo" value="Servicio"
                               class="w-4 h-4 bg-[#161618] border-gray-700 text-cyan-400 focus:ring-cyan-400/50 focus:ring-offset-0">
                        <div>
                            <div class="text-sm font-medium text-gray-100">Servicio</div>
                            <div class="text-xs text-gray-500">Trabajo o reparación</div>
                        </div>
                    </label>
                </div>
                @error('tipo') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Nombre <span class="text-red-400">*</span></label>
                    <input wire:model="nombre" type="text"
                           class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                           placeholder="Ej: Teclado Mecánico RGB">
                    @error('nombre') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Categoría <span class="text-red-400">*</span></label>
                    <select wire:model="idCategoria"
                            class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all">
                        <option value="" class="text-gray-500">Seleccione una categoría</option>
                        @foreach ($categorias as $cat)
                            <option value="{{ $cat->idCategoria }}">{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                    @error('idCategoria') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Precio Unitario <span class="text-red-400">*</span></label>
                    <input wire:model="precioUnitario" type="number" step="0.01" min="0"
                           class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                           placeholder="0.00">
                    @error('precioUnitario') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Garantía</label>
                    <input wire:model="garantia" type="text"
                           class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                           placeholder="Ej: 12 meses">
                    @error('garantia') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Campos exclusivos de Producto -->
            <div x-show="tipo === 'Producto'" x-transition class="space-y-4 pt-2 border-t border-gray-800/50">
                <p class="text-xs font-mono text-cyan-400 uppercase tracking-wider">Datos del Producto Físico</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Stock Inicial <span class="text-red-400">*</span></label>
                        <input wire:model="stock" type="number" min="0"
                               class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                               placeholder="0">
                        @error('stock') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Marca</label>
                        <input wire:model="marca" type="text"
                               class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                               placeholder="Ej: Logitech">
                        @error('marca') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Modelo</label>
                        <input wire:model="modelo" type="text"
                               class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                               placeholder="Ej: G Pro X">
                        @error('modelo') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Número de Serie</label>
                        <input wire:model="numeroSerie" type="text"
                               class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                               placeholder="Ej: SN123456789">
                        @error('numeroSerie') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex items-center gap-3 pt-2">
                <button type="button" wire:click="cancelar"
                        class="px-5 py-2.5 text-sm font-medium text-gray-300 bg-gray-800 border border-gray-700 rounded-lg hover:bg-gray-700 transition-all">
                    Cancelar
                </button>
                <button type="submit"
                        class="px-5 py-2.5 text-sm font-bold text-black bg-cyan-400 rounded-lg hover:bg-cyan-300 neon-glow transition-all">
                    Guardar Registro
                </button>
            </div>
        </form>
    </div>
</div>
