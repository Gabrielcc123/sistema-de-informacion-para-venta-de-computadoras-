<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\NotaVenta;
use App\Models\DetalleVenta;
use App\Models\Pago;
use App\Models\Producto;
use App\Models\Orden;
use App\Models\Usuario;

new #[Layout('components.layouts.app')] class extends Component {
    public function with(): array
    {
        $inicioMes = now()->startOfMonth()->toDateString();
        $finMes = now()->endOfMonth()->toDateString();

        $ventasMes = NotaVenta::whereBetween('fecha', [$inicioMes, $finMes])->sum('total') ?? 0;
        $cantidadVentasMes = NotaVenta::whereBetween('fecha', [$inicioMes, $finMes])->count();

        $productoMasVendido = DetalleVenta::selectRaw('idProductoServicio, SUM(cantidad) as total_vendido')
            ->whereHas('notaVenta', fn($q) => $q->whereBetween('fecha', [$inicioMes, $finMes]))
            ->groupBy('idProductoServicio')
            ->orderByDesc('total_vendido')
            ->with('productoServicio')
            ->first();

        $pagoMasUsado = NotaVenta::selectRaw('idPago, COUNT(*) as total')
            ->whereBetween('fecha', [$inicioMes, $finMes])
            ->groupBy('idPago')
            ->orderByDesc('total')
            ->with('pago')
            ->first();

        $stockBajo = Producto::where('stock', '<', 5)->with('datosGenerales')->get();

        $ordenesPendientes = Orden::where('estado', 'Pendiente')->count();
        $ordenesDiagnostico = Orden::where('estado', 'En diagnóstico')->count();
        $ordenesReparacion = Orden::where('estado', 'En reparación')->count();
        $ordenesFinalizadas = Orden::where('estado', 'Finalizado')
            ->whereMonth('updated_at', now()->month)
            ->count();

        $tecnicos = Usuario::where('tipoTecnico', 1)->where('estado', 1)->get()->map(function ($t) {
            $ordenesActivas = Orden::where('idTecnico', $t->idUsuario)
                ->whereNotIn('estado', ['Entregado'])
                ->count();
            $finalizadasMes = Orden::where('idTecnico', $t->idUsuario)
                ->where('estado', 'Finalizado')
                ->whereMonth('updated_at', now()->month)
                ->count();
            return [
                'nombre' => $t->nombre . ' ' . $t->apellido,
                'ordenesActivas' => $ordenesActivas,
                'finalizadasMes' => $finalizadasMes,
            ];
        });

        return [
            'ventasMes' => $ventasMes,
            'cantidadVentasMes' => $cantidadVentasMes,
            'productoMasVendido' => $productoMasVendido,
            'pagoMasUsado' => $pagoMasUsado,
            'stockBajo' => $stockBajo,
            'ordenesPendientes' => $ordenesPendientes,
            'ordenesDiagnostico' => $ordenesDiagnostico,
            'ordenesReparacion' => $ordenesReparacion,
            'ordenesFinalizadas' => $ordenesFinalizadas,
            'tecnicos' => $tecnicos,
        ];
    }
};
?>

<div class="space-y-6" x-data="{ tab: 'ventas' }">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-100">Reportes y Métricas</h2>
            <p class="text-sm text-gray-400 mt-1">Análisis integral del rendimiento del sistema.</p>
        </div>
        <span class="text-xs font-mono text-gray-500">Período: {{ now()->startOfMonth()->format('d/m/Y') }} — {{ now()->endOfMonth()->format('d/m/Y') }}</span>
    </div>

    <!-- Tabs -->
    <div class="flex gap-2 border-b border-gray-800">
        <button @click="tab = 'ventas'"
                :class="tab === 'ventas' ? 'text-cyan-400 border-cyan-400' : 'text-gray-400 border-transparent hover:text-gray-200'"
                class="px-4 py-3 text-sm font-medium border-b-2 transition-all">
            Ventas
        </button>
        <button @click="tab = 'inventario'"
                :class="tab === 'inventario' ? 'text-cyan-400 border-cyan-400' : 'text-gray-400 border-transparent hover:text-gray-200'"
                class="px-4 py-3 text-sm font-medium border-b-2 transition-all">
            Inventario
        </button>
        <button @click="tab = 'soporte'"
                :class="tab === 'soporte' ? 'text-cyan-400 border-cyan-400' : 'text-gray-400 border-transparent hover:text-gray-200'"
                class="px-4 py-3 text-sm font-medium border-b-2 transition-all">
            Soporte Técnico
        </button>
    </div>

    <!-- Tab: Ventas -->
    <div x-show="tab === 'ventas'" x-transition>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-[#202022] border border-gray-800 rounded-xl p-5 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-cyan-400/5 rounded-bl-full -mr-4 -mt-4"></div>
                <span class="text-xs font-mono text-gray-500 uppercase tracking-wider">Total Ventas del Mes</span>
                <div class="text-3xl font-bold text-cyan-400 mt-2">Bs. {{ number_format($ventasMes, 2) }}</div>
                <div class="text-xs text-gray-500 mt-1">{{ $cantidadVentasMes }} nota(s) registrada(s)</div>
            </div>

            <div class="bg-[#202022] border border-gray-800 rounded-xl p-5 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-400/5 rounded-bl-full -mr-4 -mt-4"></div>
                <span class="text-xs font-mono text-gray-500 uppercase tracking-wider">Producto Estrella</span>
                @if($productoMasVendido && $productoMasVendido->productoServicio)
                    <div class="text-lg font-bold text-gray-100 mt-2">{{ $productoMasVendido->productoServicio->nombre }}</div>
                    <div class="text-xs text-emerald-400 mt-1">{{ $productoMasVendido->total_vendido }} unidades vendidas</div>
                @else
                    <div class="text-lg font-bold text-gray-500 mt-2">Sin datos</div>
                @endif
            </div>

            <div class="bg-[#202022] border border-gray-800 rounded-xl p-5 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-purple-400/5 rounded-bl-full -mr-4 -mt-4"></div>
                <span class="text-xs font-mono text-gray-500 uppercase tracking-wider">Método de Pago Favorito</span>
                @if($pagoMasUsado && $pagoMasUsado->pago)
                    <div class="text-lg font-bold text-gray-100 mt-2">{{ $pagoMasUsado->pago->tipoPago }}</div>
                    <div class="text-xs text-purple-400 mt-1">{{ $pagoMasUsado->total }} transacciones</div>
                @else
                    <div class="text-lg font-bold text-gray-500 mt-2">Sin datos</div>
                @endif
            </div>
        </div>

        <div class="bg-[#202022] border border-gray-800 rounded-xl p-5">
            <h3 class="text-sm font-bold text-gray-100 uppercase tracking-wider mb-3">Top 5 Productos Más Vendidos</h3>
            @php
                $topProductos = DetalleVenta::selectRaw('idProductoServicio, SUM(cantidad) as total_vendido, SUM(subTotal) as total_ingreso')
                    ->whereHas('notaVenta', fn($q) => $q->whereBetween('fecha', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()]))
                    ->groupBy('idProductoServicio')
                    ->orderByDesc('total_vendido')
                    ->with('productoServicio')
                    ->limit(5)
                    ->get();
            @endphp
            <div class="space-y-3">
                @forelse($topProductos as $i => $item)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full bg-cyan-400/10 text-cyan-400 text-xs font-bold flex items-center justify-center border border-cyan-400/30">{{ $i + 1 }}</span>
                            <span class="text-sm text-gray-100">{{ $item->productoServicio->nombre ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center gap-4 text-sm">
                            <span class="text-gray-400">{{ $item->total_vendido }} uds.</span>
                            <span class="font-mono text-cyan-400">Bs. {{ number_format($item->total_ingreso, 2) }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No hay datos de ventas este mes.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Tab: Inventario -->
    <div x-show="tab === 'inventario'" x-transition>
        @if($stockBajo->count() > 0)
            <div class="bg-[#202022] border border-red-400/30 rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-red-400/20 bg-red-400/5">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                        <h3 class="text-sm font-bold text-red-400 uppercase tracking-wider">Alerta de Stock Bajo — {{ $stockBajo->count() }} producto(s) con menos de 5 unidades</h3>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-[#161618] text-gray-400 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-5 py-3">Producto</th>
                                <th class="px-5 py-3">Marca / Modelo</th>
                                <th class="px-5 py-3">Stock Actual</th>
                                <th class="px-5 py-3">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800 text-gray-300">
                            @foreach($stockBajo as $prod)
                                <tr class="hover:bg-[#262629] transition-colors">
                                    <td class="px-5 py-3 text-gray-100 font-medium">{{ $prod->datosGenerales->nombre ?? '—' }}</td>
                                    <td class="px-5 py-3">{{ $prod->marca ?? '—' }} {{ $prod->modelo ?? '' }}</td>
                                    <td class="px-5 py-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-red-400/10 text-red-400 border border-red-400/30">
                                            {{ $prod->stock }} uds
                                        </span>
                                    </td>
                                    <td class="px-5 py-3">
                                        <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold border {{ $prod->estado === 'Recibido' ? 'bg-blue-400/10 text-blue-400 border-blue-400/20' : 'bg-amber-400/10 text-amber-400 border-amber-400/20' }}">
                                            {{ $prod->estado }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="bg-[#202022] border border-gray-800 rounded-xl p-12 text-center">
                <svg class="w-12 h-12 text-emerald-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-gray-400 text-sm">Todos los productos tienen stock suficiente.</p>
            </div>
        @endif
    </div>

    <!-- Tab: Soporte Técnico -->
    <div x-show="tab === 'soporte'" x-transition>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-[#202022] border border-blue-400/20 rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-blue-400">{{ $ordenesPendientes }}</div>
                <div class="text-xs text-gray-500 uppercase tracking-wider mt-1">Pendientes</div>
            </div>
            <div class="bg-[#202022] border border-amber-400/20 rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-amber-400">{{ $ordenesDiagnostico }}</div>
                <div class="text-xs text-gray-500 uppercase tracking-wider mt-1">En Diagnóstico</div>
            </div>
            <div class="bg-[#202022] border border-purple-400/20 rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-purple-400">{{ $ordenesReparacion }}</div>
                <div class="text-xs text-gray-500 uppercase tracking-wider mt-1">En Reparación</div>
            </div>
            <div class="bg-[#202022] border border-emerald-400/20 rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-emerald-400">{{ $ordenesFinalizadas }}</div>
                <div class="text-xs text-gray-500 uppercase tracking-wider mt-1">Finalizadas (mes)</div>
            </div>
        </div>

        <div class="bg-[#202022] border border-gray-800 rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-800">
                <h3 class="text-sm font-bold text-gray-100 uppercase tracking-wider">Rendimiento de Técnicos</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-[#161618] text-gray-400 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-5 py-3">Técnico</th>
                            <th class="px-5 py-3">Órdenes Activas</th>
                            <th class="px-5 py-3">Finalizadas (mes)</th>
                            <th class="px-5 py-3">Rendimiento</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800 text-gray-300">
                        @forelse($tecnicos as $tec)
                            <tr class="hover:bg-[#262629] transition-colors">
                                <td class="px-5 py-3 font-medium text-gray-100">{{ $tec['nombre'] }}</td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex px-2.5 py-0.5 rounded text-xs font-semibold bg-amber-400/10 text-amber-400 border border-amber-400/20">{{ $tec['ordenesActivas'] }}</span>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex px-2.5 py-0.5 rounded text-xs font-semibold bg-emerald-400/10 text-emerald-400 border border-emerald-400/20">{{ $tec['finalizadasMes'] }}</span>
                                </td>
                                <td class="px-5 py-3">
                                    @php
                                        $maxFinalizadas = $tecnicos->max('finalizadasMes');
                                        $porcentaje = $maxFinalizadas > 0 ? round(($tec['finalizadasMes'] / $maxFinalizadas) * 100) : 0;
                                    @endphp
                                    <div class="w-full bg-gray-800 rounded-full h-2.5">
                                        <div class="bg-cyan-400 h-2.5 rounded-full" style="width: {{ $porcentaje }}%"></div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-gray-500">No hay técnicos registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>