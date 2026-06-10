<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>


<?php
    $ordenesActivas = \App\Models\Orden::where('idTecnico', Auth::id())
        ->whereNotIn('estado', ['Entregado'])
        ->get();
    $asignadas = $ordenesActivas->count();
    $enProceso = $ordenesActivas->whereIn('estado', ['En diagnóstico', 'En reparación'])->count();
    $finalizadasHoy = \App\Models\Orden::where('idTecnico', Auth::id())
        ->where('estado', 'Finalizado')
        ->whereDate('updated_at', now()->toDateString())
        ->count();
?>

<div class="space-y-6">
    <div class="flex items-center justify-between mb-2">
        <div>
            <h2 class="text-2xl font-bold text-gray-100">Panel Principal - Servicio Técnico</h2>
            <p class="text-sm text-gray-400 mt-1">Gestión de órdenes y equipos asignados.</p>
        </div>
        <a href="<?php echo e(route('ordenes.mis-ordenes')); ?>" wire:navigate
           class="px-4 py-2 bg-cyan-400 text-black text-sm font-bold rounded-lg neon-glow hover:bg-cyan-300 transition-all duration-200 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.032 4.032m-1.745 1.437l.102.085" />
            </svg>
            Mis Órdenes
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-[#202022] border border-gray-800 rounded-xl p-5 relative overflow-hidden group hover:border-gray-700 transition-all">
            <div class="absolute top-0 right-0 w-24 h-24 bg-amber-400/5 rounded-bl-full -mr-4 -mt-4"></div>
            <div class="flex justify-between items-start mb-4">
                <span class="text-xs font-mono text-gray-500 uppercase tracking-wider">Órdenes Asignadas</span>
                <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.032 4.032m-1.745 1.437l.102.085" />
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-100 mb-1"><?php echo e($asignadas); ?></div>
            <div class="text-xs text-amber-400">Órdenes activas</div>
        </div>

        <div class="bg-[#202022] border border-gray-800 rounded-xl p-5 relative overflow-hidden group hover:border-gray-700 transition-all">
            <div class="absolute top-0 right-0 w-24 h-24 bg-purple-400/5 rounded-bl-full -mr-4 -mt-4"></div>
            <div class="flex justify-between items-start mb-4">
                <span class="text-xs font-mono text-gray-500 uppercase tracking-wider">En Proceso</span>
                <svg class="w-5 h-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182" />
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-100 mb-1"><?php echo e($enProceso); ?></div>
            <div class="text-xs text-purple-400">Diagnóstico / Reparación</div>
        </div>

        <div class="bg-[#202022] border border-gray-800 rounded-xl p-5 relative overflow-hidden group hover:border-gray-700 transition-all">
            <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-400/5 rounded-bl-full -mr-4 -mt-4"></div>
            <div class="flex justify-between items-start mb-4">
                <span class="text-xs font-mono text-gray-500 uppercase tracking-wider">Finalizadas Hoy</span>
                <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-100 mb-1"><?php echo e($finalizadasHoy); ?></div>
            <div class="text-xs text-emerald-400">Listas para entrega</div>
        </div>
    </div>
</div>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $attributes = $__attributesOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__attributesOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $component = $__componentOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__componentOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?><?php /**PATH C:\Users\VICTUS\OneDrive\Desktop\Metodologia\Fase3 - bitacora , login , requisotos de password - copia\resources\views/livewire/dashboard/tecnico.blade.php ENDPATH**/ ?>