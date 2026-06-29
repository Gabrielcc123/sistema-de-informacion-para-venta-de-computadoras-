<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Cliente;
use App\Models\Pago;
use App\Models\ProductoServicio;
use App\Models\Producto;
use App\Models\NotaVenta;
use App\Models\DetalleVenta;
use App\Models\Orden;
use App\Models\Equipo;
use App\Models\Usuario;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-100">Registrar Venta</h2>
            <p class="text-sm text-gray-400 mt-1">Complete los datos del cliente, método de pago y agregue los ítems.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Columna Izquierda: Datos de la venta -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Datos de cabecera -->
            <div class="bg-[#202022] border border-gray-800 rounded-xl p-6 space-y-5">
                <h3 class="text-sm font-bold text-gray-100 uppercase tracking-wider border-b border-gray-800 pb-3">Datos de la Venta</h3>

                <div>
                    <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Cliente <span class="text-red-400">*</span></label>
                    <select wire:model="idCliente"
                            class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all">
                        <option value="" class="text-gray-500">Seleccione un cliente</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <option value="<?php echo e($cliente->idCliente); ?>"><?php echo e($cliente->apellido); ?> <?php echo e($cliente->nombre); ?> (CI: <?php echo e($cliente->ci); ?>)</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['idCliente'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div>
                    <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Método de Pago <span class="text-red-400">*</span></label>
                    <select wire:model="idPago"
                            class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all">
                        <option value="" class="text-gray-500">Seleccione método</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $pagos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pago): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <option value="<?php echo e($pago->idPago); ?>"><?php echo e($pago->tipoPago); ?></option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['idPago'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="pt-2 border-t border-gray-800/50">
                    <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Agregar Ítem</label>
                    <div class="flex gap-2">
                        <select wire:model="productoSeleccionado"
                                class="flex-1 bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all">
                            <option value="" class="text-gray-500">Seleccione producto o servicio</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <option value="<?php echo e($prod->idProductoServicio); ?>"><?php echo e($prod->nombre); ?> — Bs. <?php echo e(number_format($prod->precioUnitario, 2)); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                        <button type="button" wire:click="agregarItem"
                                class="px-3 py-2 bg-cyan-400 text-black rounded-lg hover:bg-cyan-300 transition-all font-bold text-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </button>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['productoSeleccionado'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($requiereOrden): ?>
                <!-- Bloque Orden de Servicio Técnico -->
                <div class="bg-[#202022] border border-cyan-400/30 rounded-xl p-6 space-y-5 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1 h-full bg-cyan-400"></div>
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.032 4.032m-1.745 1.437l.102.085" />
                        </svg>
                        <h3 class="text-sm font-bold text-cyan-400 uppercase tracking-wider">Orden de Servicio Técnico</h3>
                    </div>
                    <p class="text-xs text-gray-400">El carrito contiene servicios. Complete los datos para generar la orden de servicio.</p>

                    <div>
                        <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Equipo <span class="text-red-400">*</span></label>
                        <select wire:model="idEquipo"
                                class="w-full bg-[#161618] border border-cyan-400/30 text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all">
                            <option value="" class="text-gray-500">Seleccione un equipo</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $equipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <option value="<?php echo e($eq->idEquipo); ?>">#<?php echo e($eq->idEquipo); ?> — <?php echo e($eq->marca ?? 'S/M'); ?> <?php echo e($eq->modelo ?? ''); ?> <?php echo e($eq->numeroSerie ? '(S/N: ' . $eq->numeroSerie . ')' : ''); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['idEquipo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Asignar Técnico <span class="text-red-400">*</span></label>
                        <select wire:model="idTecnico"
                                class="w-full bg-[#161618] border border-cyan-400/30 text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all">
                            <option value="" class="text-gray-500">Seleccione un técnico</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $tecnicos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <option value="<?php echo e($tec->idUsuario); ?>"><?php echo e($tec->nombre); ?> <?php echo e($tec->apellido); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['idTecnico'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Botón Cancelar -->
            <button type="button" wire:click="cancelar"
                    class="w-full px-5 py-2.5 text-sm font-medium text-gray-300 bg-gray-800 border border-gray-700 rounded-lg hover:bg-gray-700 transition-all">
                Cancelar y Volver
            </button>
        </div>

        <!-- Columna Derecha: Carrito / Detalle -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-[#202022] border border-gray-800 rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-800 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-100 uppercase tracking-wider">Detalle de la Venta</h3>
                    <span class="text-xs text-gray-500"><?php echo e(count($items)); ?> ítem(s)</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm min-w-[600px]">
                        <thead class="bg-[#161618] text-gray-400 text-xs uppercase font-semibold border-b border-gray-800">
                            <tr>
                                <th class="px-5 py-3">Producto / Servicio</th>
                                <th class="px-5 py-3 w-28">Cantidad</th>
                                <th class="px-5 py-3">Precio Unit.</th>
                                <th class="px-5 py-3">Subtotal</th>
                                <th class="px-5 py-3 text-right w-16"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800 text-gray-300">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                <tr class="hover:bg-[#262629] transition-colors">
                                    <td class="px-5 py-3 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item['tipo'] === 'Servicio'): ?>
                                                <span class="inline-flex px-1.5 py-0.5 rounded text-[10px] font-semibold bg-purple-400/10 text-purple-400 border border-purple-400/20">SVC</span>
                                            <?php else: ?>
                                                <span class="inline-flex px-1.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-400/10 text-emerald-400 border border-emerald-400/20">PROD</span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <span class="text-gray-100"><?php echo e($item['nombre']); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3 whitespace-nowrap">
                                        <input type="number" min="1"
                                               wire:model="items.<?php echo e($index); ?>.cantidad"
                                               wire:change="calcularTotal"
                                               class="w-20 bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-2 py-1.5 text-sm text-center focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all">
                                    </td>
                                    <td class="px-5 py-3 whitespace-nowrap font-mono text-gray-100">
                                        Bs. <?php echo e(number_format($item['precioUnitario'], 2)); ?>

                                    </td>
                                    <td class="px-5 py-3 whitespace-nowrap font-mono font-bold text-cyan-400">
                                        Bs. <?php echo e(number_format($item['subtotal'], 2)); ?>

                                    </td>
                                    <td class="px-5 py-3 whitespace-nowrap text-right">
                                        <button type="button" wire:click="eliminarItem(<?php echo e($index); ?>)"
                                                class="p-1.5 rounded border border-red-400/30 text-red-400 hover:bg-red-400/10 transition-all">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                <tr>
                                    <td colspan="5" class="px-5 py-10 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <svg class="w-8 h-8 text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                            </svg>
                                            El carrito está vacío. Agregue productos o servicios.
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Total y Confirmar -->
            <div class="bg-[#202022] border border-gray-800 rounded-xl p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-center sm:text-left">
                    <p class="text-xs font-mono text-gray-400 uppercase tracking-wider">Total a Pagar</p>
                    <p class="text-3xl font-bold text-cyan-400">Bs. <?php echo e(number_format($total, 2)); ?></p>
                </div>
                <button type="button"
                        x-data
                        x-on:click="$el.disabled = true; $el.innerHTML = 'Procesando...'; $wire.guardar()"
                        class="w-full sm:w-auto px-8 py-3 text-base font-bold text-black bg-cyan-400 rounded-lg hover:bg-cyan-300 neon-glow transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Confirmar Venta
                </button>
            </div>
        </div>
    </div>

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
</div><?php /**PATH /home/gabriel/Escritorio/Metodologia/sistema-de-informacion-para-venta-de-computadoras-/resources/views/livewire/ventas/crear.blade.php ENDPATH**/ ?>