<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Equipo;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;

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
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $equipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr class="hover:bg-[#262629] transition-colors">
                            <td class="px-5 py-3 font-mono text-xs text-gray-500">#<?php echo e($equipo->idEquipo); ?></td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <div class="font-medium text-gray-100"><?php echo e($equipo->marca ?? '—'); ?> <?php echo e($equipo->modelo ?? ''); ?></div>
                            </td>
                            <td class="px-5 py-3 font-mono text-xs"><?php echo e($equipo->numeroSerie ?? '—'); ?></td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <?php
                                    $gamaColors = ['Básica' => 'text-gray-400 border-gray-400/20 bg-gray-400/10', 'Media' => 'text-cyan-400 border-cyan-400/20 bg-cyan-400/10', 'Alta' => 'text-amber-400 border-amber-400/20 bg-amber-400/10'];
                                ?>
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold border <?php echo e($gamaColors[$equipo->gama] ?? 'text-gray-400 border-gray-400/20 bg-gray-400/10'); ?>">
                                    <?php echo e($equipo->gama); ?>

                                </span>
                            </td>
                            <td class="px-5 py-3 max-w-xs truncate text-sm"><?php echo e($equipo->descripcion ?? '—'); ?></td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <?php
                                    $estadoColors = [
                                        'Recibido' => 'text-blue-400 border-blue-400/20 bg-blue-400/10',
                                        'En Diagnóstico' => 'text-amber-400 border-amber-400/20 bg-amber-400/10',
                                        'En Reparación' => 'text-purple-400 border-purple-400/20 bg-purple-400/10',
                                        'Listo' => 'text-emerald-400 border-emerald-400/20 bg-emerald-400/10',
                                        'Entregado' => 'text-gray-400 border-gray-400/20 bg-gray-400/10',
                                    ];
                                ?>
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold border <?php echo e($estadoColors[$equipo->estado] ?? 'text-gray-400 border-gray-400/20 bg-gray-400/10'); ?>">
                                    <?php echo e($equipo->estado); ?>

                                </span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="abrirModal(<?php echo e($equipo->idEquipo); ?>)"
                                            class="px-3 py-1.5 text-xs font-medium rounded border border-cyan-400/30 text-cyan-400 hover:bg-cyan-400/10 transition-all duration-200">
                                        Editar
                                    </button>
                                    <button wire:click="eliminar(<?php echo e($equipo->idEquipo); ?>)"
                                            wire:confirm="¿Está seguro de eliminar este equipo?"
                                            class="px-3 py-1.5 text-xs font-medium rounded border border-red-400/30 text-red-400 hover:bg-red-400/10 transition-all duration-200">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
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
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($equipos->hasPages()): ?>
            <div class="px-5 py-4 border-t border-gray-800 bg-[#161618]">
                <?php echo e($equipos->links(data: ['scrollTo' => false])); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($mostrarModal): ?>
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
             wire:click.self="cerrarModal">
            <div class="bg-[#202022] border border-gray-800 rounded-xl p-6 w-full max-w-lg shadow-2xl mx-4">
                <h3 class="text-lg font-bold text-gray-100 mb-1">
                    <?php echo e($modoEdicion ? 'Editar Equipo' : 'Registrar Equipo'); ?>

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
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['gama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-1">Descripción del problema</label>
                        <textarea wire:model="descripcion" rows="3"
                                  class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600 resize-none"
                                  placeholder="Describa el problema reportado..."></textarea>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($modoEdicion): ?>
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
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['estado'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="flex items-center gap-3 mt-6">
                    <button wire:click="cerrarModal"
                            class="flex-1 px-4 py-2 text-sm font-medium text-gray-300 bg-gray-800 border border-gray-700 rounded-lg hover:bg-gray-700 transition-all">
                        Cancelar
                    </button>
                    <button wire:click="guardar"
                            class="flex-1 px-4 py-2 text-sm font-bold text-black bg-cyan-400 rounded-lg hover:bg-cyan-300 neon-glow transition-all">
                        <?php echo e($modoEdicion ? 'Guardar Cambios' : 'Registrar Equipo'); ?>

                    </button>
                </div>
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
</div><?php /**PATH C:\Users\VICTUS\OneDrive\Desktop\Metodologia\Fase3 - bitacora , login , requisotos de password - copia\resources\views\livewire/equipos/index.blade.php ENDPATH**/ ?>