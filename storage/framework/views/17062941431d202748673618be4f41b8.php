<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Usuario;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

?>

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
                    <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Apellido <span class="text-red-400">*</span></label>
                    <input wire:model="apellido" type="text"
                           class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                           placeholder="Ej: Martínez">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['apellido'];
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
                    <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Correo Electrónico <span class="text-red-400">*</span></label>
                    <input wire:model="email" type="email"
                           class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                           placeholder="ejemplo@iriscomputer.com">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div>
                    <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Teléfono</label>
                    <input wire:model="telefono" type="text"
                           class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all placeholder:text-gray-600"
                           placeholder="Ej: 71234567">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['telefono'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['roles'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-400 text-xs mt-2"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
</div><?php /**PATH /home/gabriel/Escritorio/Metodologia/sistema-de-informacion-para-venta-de-computadoras-/resources/views/livewire/usuarios/crear.blade.php ENDPATH**/ ?>