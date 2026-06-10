<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\ProductoServicio;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

?>

<div class="space-y-6" x-data="{ tipo: <?php if ((object) ('tipo') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('tipo'->value()); ?>')<?php echo e('tipo'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('tipo'); ?>')<?php endif; ?> }">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-100">Inventario y Servicios</h2>
            <p class="text-sm text-gray-400 mt-1">Catálogo de productos y servicios disponibles.</p>
        </div>
        <button wire:click="abrirModal(null)"
                class="inline-flex items-center gap-2 px-4 py-2 bg-cyan-400 text-black text-sm font-bold rounded-lg neon-glow hover:bg-cyan-300 transition-all duration-200">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Nuevo Registro
        </button>
    </div>

    <div class="bg-[#202022] border border-gray-800 rounded-xl px-5 py-4">
        <div class="relative max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por nombre o categoría..."
                   class="block w-full pl-10 pr-3 py-2 border border-gray-700 rounded-lg bg-[#161618] text-gray-100 placeholder-gray-600 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 sm:text-sm transition-all">
        </div>
    </div>

    <div class="bg-[#202022] border border-gray-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm min-w-[800px]">
                <thead class="bg-[#161618] text-gray-400 text-xs uppercase font-semibold border-b border-gray-800">
                    <tr>
                        <th class="px-5 py-3">Tipo</th>
                        <th class="px-5 py-3">Nombre</th>
                        <th class="px-5 py-3">Categoría</th>
                        <th class="px-5 py-3">Precio</th>
                        <th class="px-5 py-3">Stock</th>
                        <th class="px-5 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800 text-gray-300">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr class="hover:bg-[#262629] transition-colors">
                            <td class="px-5 py-3 whitespace-nowrap">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->tipo === 'Servicio'): ?>
                                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold bg-purple-400/10 text-purple-400 border border-purple-400/20">Servicio</span>
                                <?php else: ?>
                                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold bg-emerald-400/10 text-emerald-400 border border-emerald-400/20">Producto</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap text-gray-100"><?php echo e($item->nombre); ?></td>
                            <td class="px-5 py-3 whitespace-nowrap"><?php echo e($item->categoria?->nombre ?? '—'); ?></td>
                            <td class="px-5 py-3 whitespace-nowrap font-mono text-gray-100">Bs. <?php echo e(number_format($item->precioUnitario, 2)); ?></td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->tipo === 'Servicio'): ?>
                                    <span class="text-gray-500">—</span>
                                <?php else: ?>
                                    <?php $stock = $item->productoFisico?->stock ?? 0; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($stock < 5): ?>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-semibold bg-red-400/10 text-red-400 border border-red-400/20"><?php echo e($stock); ?> <span class="opacity-70">Stock Bajo</span></span>
                                    <?php else: ?>
                                        <span class="text-gray-100"><?php echo e($stock); ?></span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="abrirModal(<?php echo e($item->idProductoServicio); ?>)"
                                            class="px-3 py-1.5 text-xs font-medium rounded border border-cyan-400/30 text-cyan-400 hover:bg-cyan-400/10 transition-all duration-200">
                                        Editar
                                    </button>
                                    <button wire:click="eliminar(<?php echo e($item->idProductoServicio); ?>)"
                                            wire:confirm="¿Está seguro de eliminar <?php echo e($item->nombre); ?>?"
                                            class="px-3 py-1.5 text-xs font-medium rounded border border-red-400/30 text-red-400 hover:bg-red-400/10 transition-all duration-200">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <svg class="w-8 h-8 text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                    </svg>
                                    No hay registros en el inventario.
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($items->hasPages()): ?>
            <div class="px-5 py-4 border-t border-gray-800 bg-[#161618]">
                <?php echo e($items->links(data: ['scrollTo' => false])); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($mostrarModal): ?>
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
             wire:click.self="cerrarModal">
            <div class="bg-[#202022] border border-gray-800 rounded-xl p-6 w-full max-w-2xl shadow-2xl mx-4 max-h-[90vh] overflow-y-auto">
                <h3 class="text-lg font-bold text-gray-100 mb-1">
                    <?php echo e($modoEdicion ? 'Editar Registro' : 'Nuevo Registro'); ?>

                </h3>
                <p class="text-sm text-gray-400 mb-5">Complete los datos del <?php echo e($modoEdicion ? 'registro' : 'producto o servicio'); ?>.</p>

                <form wire:submit="guardar" class="space-y-5">
                    <div>
                        <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-3">Tipo <span class="text-red-400">*</span></label>
                        <div class="flex flex-wrap gap-4">
                            <label class="flex items-center gap-3 px-4 py-3 bg-[#161618] border border-gray-800 rounded-lg cursor-pointer hover:border-cyan-400/50 transition-all select-none"
                                   :class="tipo === 'Producto' ? 'border-cyan-400/50 ring-1 ring-cyan-400/30' : ''">
                                <input type="radio" wire:model.live="tipo" value="Producto"
                                       class="w-4 h-4 bg-[#161618] border-gray-700 text-cyan-400 focus:ring-cyan-400/50 focus:ring-offset-0"
                                       <?php if($modoEdicion): ?> disabled <?php endif; ?>>
                                <div>
                                    <div class="text-sm font-medium text-gray-100">Producto</div>
                                    <div class="text-xs text-gray-500">Artículo físico con stock</div>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 px-4 py-3 bg-[#161618] border border-gray-800 rounded-lg cursor-pointer hover:border-cyan-400/50 transition-all select-none"
                                   :class="tipo === 'Servicio' ? 'border-cyan-400/50 ring-1 ring-cyan-400/30' : ''">
                                <input type="radio" wire:model.live="tipo" value="Servicio"
                                       class="w-4 h-4 bg-[#161618] border-gray-700 text-cyan-400 focus:ring-cyan-400/50 focus:ring-offset-0"
                                       <?php if($modoEdicion): ?> disabled <?php endif; ?>>
                                <div>
                                    <div class="text-sm font-medium text-gray-100">Servicio</div>
                                    <div class="text-xs text-gray-500">Trabajo o reparación</div>
                                </div>
                            </label>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['tipo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Nombre <span class="text-red-400">*</span></label>
                            <input wire:model="nombre" type="text"
                                   class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                                   placeholder="Ej: Teclado Mecánico RGB">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div>
                            <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Categoría <span class="text-red-400">*</span></label>
                            <select wire:model="idCategoria"
                                    class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all">
                                <option value="" class="text-gray-500">Seleccione una categoría</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <option value="<?php echo e($cat->idCategoria); ?>"><?php echo e($cat->nombre); ?></option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </select>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['idCategoria'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Precio Unitario <span class="text-red-400">*</span></label>
                            <input wire:model="precioUnitario" type="number" step="0.01" min="0"
                                   class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                                   placeholder="0.00">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['precioUnitario'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div>
                            <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Garantía</label>
                            <input wire:model="garantia" type="text"
                                   class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                                   placeholder="Ej: 12 meses">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['garantia'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <div x-show="tipo === 'Producto'" x-transition class="space-y-4 pt-2 border-t border-gray-800/50">
                        <p class="text-xs font-mono text-cyan-400 uppercase tracking-wider">Datos del Producto Físico</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Stock <span class="text-red-400">*</span></label>
                                <input wire:model="stock" type="number" min="0"
                                       class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                                       placeholder="0">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['stock'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Marca</label>
                                <input wire:model="marca" type="text"
                                       class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                                       placeholder="Ej: Logitech">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['marca'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Modelo</label>
                                <input wire:model="modelo" type="text"
                                       class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                                       placeholder="Ej: G Pro X">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['modelo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Número de Serie</label>
                                <input wire:model="numeroSerie" type="text"
                                       class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                                       placeholder="Ej: SN123456789">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['numeroSerie'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="button" wire:click="cerrarModal"
                                class="flex-1 px-4 py-2 text-sm font-medium text-gray-300 bg-gray-800 border border-gray-700 rounded-lg hover:bg-gray-700 transition-all">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="flex-1 px-4 py-2 text-sm font-bold text-black bg-cyan-400 rounded-lg hover:bg-cyan-300 neon-glow transition-all">
                            <?php echo e($modoEdicion ? 'Guardar Cambios' : 'Guardar Registro'); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

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
</div><?php /**PATH C:\Users\VICTUS\OneDrive\Desktop\Metodologia\Fase3 - bitacora , login , requisotos de password - copia\resources\views\livewire/inventario/index.blade.php ENDPATH**/ ?>