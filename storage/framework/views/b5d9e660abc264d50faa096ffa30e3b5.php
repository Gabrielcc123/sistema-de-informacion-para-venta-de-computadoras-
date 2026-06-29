<div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-2">
        <div>
            <h2 class="text-2xl font-bold text-gray-100">Panel Principal - Supervisor</h2>
            <p class="text-sm text-gray-400 mt-1">Visión ejecutiva del rendimiento del sistema.</p>
        </div>
    </div>

    <!-- Métricas Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Ventas del día -->
        <div class="bg-[#202022] border border-gray-800 rounded-xl p-5 relative overflow-hidden group hover:border-gray-700 transition-all">
            <div class="absolute top-0 right-0 w-24 h-24 bg-cyan-400/5 rounded-bl-full -mr-4 -mt-4"></div>
            <div class="flex justify-between items-start mb-4">
                <span class="text-xs font-mono text-gray-500 uppercase tracking-wider">Ventas del día</span>
                <svg class="w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.895-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-100 mb-1">Bs. <?php echo e(number_format($ventasHoy, 2)); ?></div>
            <div class="text-xs text-cyan-400">Ventas del día</div>
        </div>

        <!-- Órdenes Pendientes -->
        <div class="bg-[#202022] border border-gray-800 rounded-xl p-5 relative overflow-hidden group hover:border-gray-700 transition-all">
            <div class="absolute top-0 right-0 w-24 h-24 bg-amber-400/5 rounded-bl-full -mr-4 -mt-4"></div>
            <div class="flex justify-between items-start mb-4">
                <span class="text-xs font-mono text-gray-500 uppercase tracking-wider">Órdenes Pendientes</span>
                <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.032 4.032m-1.745 1.437l.102.085" />
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-100 mb-1"><?php echo e($ordenesPendientes); ?></div>
            <div class="text-xs text-amber-400">Órdenes pendientes</div>
        </div>

        <!-- Usuarios Activos -->
        <div class="bg-[#202022] border border-gray-800 rounded-xl p-5 relative overflow-hidden group hover:border-gray-700 transition-all">
            <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-400/5 rounded-bl-full -mr-4 -mt-4"></div>
            <div class="flex justify-between items-start mb-4">
                <span class="text-xs font-mono text-gray-500 uppercase tracking-wider">Usuarios Activos</span>
                <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493A15.87 15.87 0 0112 19.5c-1.89 0-3.68-.549-5.21-1.487a4.125 4.125 0 00-7.533 2.493A9.337 9.337 0 003.375 19.5M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zM3.75 12a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-100 mb-1"><?php echo e($usuariosActivos); ?></div>
            <div class="text-xs text-emerald-400 flex items-center gap-1">En línea ahora</div>
        </div>

        <!-- Últimas Acciones -->
        <div class="bg-[#202022] border border-gray-800 rounded-xl p-5 relative overflow-hidden group hover:border-gray-700 transition-all">
            <div class="absolute top-0 right-0 w-24 h-24 bg-cyan-400/5 rounded-bl-full -mr-4 -mt-4"></div>
            <div class="flex justify-between items-start mb-4">
                <span class="text-xs font-mono text-gray-500 uppercase tracking-wider">Últimas Acciones</span>
                <svg class="w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-100 mb-1"><?php echo e($accionesHoy); ?></div>
            <div class="text-xs text-gray-500">Registradas en bitácora</div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" x-data x-init>
        <div class="bg-[#202022] border border-gray-800 rounded-xl p-5">
            <h3 class="text-sm font-bold text-gray-100 uppercase tracking-wider mb-4">Ventas Últimos 7 Días</h3>
            <div class="relative" style="height: 280px;">
                <canvas id="chartVentas" x-init="
                    new Chart(document.getElementById('chartVentas'), {
                        type: 'bar',
                        data: {
                            labels: <?php echo e(Js::from(array_keys($ventasSemana))); ?>,
                            datasets: [{
                                label: 'Ventas (Bs.)',
                                data: <?php echo e(Js::from(array_values($ventasSemana))); ?>,
                                backgroundColor: 'rgba(34, 211, 238, 0.6)',
                                borderColor: '#22d3ee',
                                borderWidth: 1,
                                borderRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false }
                            },
                            scales: {
                                x: {
                                    grid: { color: 'rgba(55, 65, 81, 0.4)' },
                                    ticks: { color: '#9ca3af', font: { size: 11 } }
                                },
                                y: {
                                    grid: { color: 'rgba(55, 65, 81, 0.4)' },
                                    ticks: { color: '#9ca3af', font: { size: 11 } },
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                "></canvas>
            </div>
        </div>

        <div class="bg-[#202022] border border-gray-800 rounded-xl p-5">
            <h3 class="text-sm font-bold text-gray-100 uppercase tracking-wider mb-4">Métodos de Pago</h3>
            <div class="relative" style="height: 280px;">
                <canvas id="chartPagos" x-init="
                    new Chart(document.getElementById('chartPagos'), {
                        type: 'doughnut',
                        data: {
                            labels: <?php echo e(Js::from(array_keys($pagosDistribucion))); ?>,
                            datasets: [{
                                data: <?php echo e(Js::from(array_values($pagosDistribucion))); ?>,
                                backgroundColor: [
                                    '#22d3ee',
                                    '#a78bfa',
                                    '#34d399',
                                    '#f59e0b',
                                    '#f87171',
                                    '#60a5fa'
                                ],
                                borderColor: '#202022',
                                borderWidth: 3
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        color: '#d1d5db',
                                        padding: 16,
                                        font: { size: 12 },
                                        usePointStyle: true,
                                        pointStyleWidth: 10
                                    }
                                }
                            },
                            cutout: '65%'
                        }
                    });
                "></canvas>
            </div>
        </div>
    </div>

    <!-- Tabla de acciones recientes (datos reales) -->
    <div class="bg-[#202022] border border-gray-800 rounded-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-800">
            <h3 class="text-sm font-bold text-gray-100 uppercase tracking-wider">Acciones Recientes del Sistema</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-[#161618] text-gray-400 text-xs uppercase font-semibold border-b border-gray-800">
                    <tr>
                        <th class="px-5 py-3">Usuario</th>
                        <th class="px-5 py-3">Acción</th>
                        <th class="px-5 py-3">Fecha / Hora</th>
                        <th class="px-5 py-3">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800 text-gray-300">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $acciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $accion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr class="hover:bg-[#262629] transition-colors">
                            <td class="px-5 py-3 font-medium text-gray-100">
                                <?php echo e($accion->usuario ? $accion->usuario->nombre . ' ' . $accion->usuario->apellido : 'Sistema'); ?>

                            </td>
                            <td class="px-5 py-3"><?php echo e($accion->accion); ?></td>
                            <td class="px-5 py-3 text-gray-500">
                                <?php echo e($accion->fecha); ?> · <?php echo e($accion->hora); ?>

                            </td>
                            <td class="px-5 py-3 text-gray-500 font-mono text-xs"><?php echo e($accion->ip); ?></td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-gray-500">
                                No hay acciones registradas recientemente.
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div><?php /**PATH /home/gabriel/Escritorio/Metodologia/sistema-de-informacion-para-venta-de-computadoras-/resources/views/livewire/dashboard/admin.blade.php ENDPATH**/ ?>