<x-layouts.app>

<?php
    $hoy = now()->toDateString();
    $ventasHoy = \App\Models\NotaVenta::where('idUsuario', Auth::id())
        ->where('fecha', $hoy)
        ->count();
    $montoHoy = \App\Models\NotaVenta::where('idUsuario', Auth::id())
        ->where('fecha', $hoy)
        ->sum('total');
    $clientesAtendidos = \App\Models\NotaVenta::where('idUsuario', Auth::id())
        ->where('fecha', $hoy)
        ->distinct('idCliente')
        ->count('idCliente');
    $ultimasVentas = \App\Models\NotaVenta::with(['cliente', 'pago'])
        ->where('idUsuario', Auth::id())
        ->orderBy('nroNotaVenta', 'desc')
        ->limit(5)
        ->get();
?>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-2">
        <div>
            <h2 class="text-2xl font-bold text-gray-100">Panel Principal - Ventas</h2>
            <p class="text-sm text-gray-400 mt-1">Resumen de tu actividad comercial del día.</p>
        </div>
        <a href="{{ route('ventas.crear') }}" wire:navigate
           class="px-4 py-2 bg-cyan-400 text-black text-sm font-bold rounded-lg neon-glow hover:bg-cyan-300 transition-all duration-200 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Nueva Venta
        </a>
    </div>

    <!-- Métricas Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Ventas Propias Hoy -->
        <div class="bg-[#202022] border border-gray-800 rounded-xl p-5 relative overflow-hidden group hover:border-gray-700 transition-all">
            <div class="absolute top-0 right-0 w-24 h-24 bg-cyan-400/5 rounded-bl-full -mr-4 -mt-4"></div>
            <div class="flex justify-between items-start mb-4">
                <span class="text-xs font-mono text-gray-500 uppercase tracking-wider">Ventas Propias (Hoy)</span>
                <svg class="w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.895-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-100 mb-1">Bs. {{ number_format($montoHoy, 2) }}</div>
            <div class="text-xs text-cyan-400">{{ $ventasHoy }} venta(s) registrada(s)</div>
        </div>

        <!-- Clientes Atendidos -->
        <div class="bg-[#202022] border border-gray-800 rounded-xl p-5 relative overflow-hidden group hover:border-gray-700 transition-all">
            <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-400/5 rounded-bl-full -mr-4 -mt-4"></div>
            <div class="flex justify-between items-start mb-4">
                <span class="text-xs font-mono text-gray-500 uppercase tracking-wider">Clientes Atendidos</span>
                <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493A15.87 15.87 0 0112 19.5c-1.89 0-3.68-.549-5.21-1.487a4.125 4.125 0 00-7.533 2.493A9.337 9.337 0 003.375 19.5M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zM3.75 12a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-100 mb-1">{{ $clientesAtendidos }}</div>
            <div class="text-xs text-emerald-400">Hoy</div>
        </div>

        <!-- Nota de Venta Rápida -->
        <a href="{{ route('ventas.crear') }}" wire:navigate
           class="bg-[#202022] border border-gray-800 rounded-xl p-5 flex flex-col justify-center items-center text-center gap-3 hover:border-cyan-400/30 transition-all cursor-pointer group no-underline">
            <div class="w-12 h-12 rounded-full bg-cyan-400/10 flex items-center justify-center border border-cyan-400/20 text-cyan-400 mb-1 group-hover:shadow-[0_0_15px_rgba(34,211,238,0.3)] transition-all">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
            </div>
            <div>
                <div class="text-sm font-bold text-gray-100">Nota de Venta Rápida</div>
                <div class="text-xs text-gray-500 mt-1">Genera una venta en segundos</div>
            </div>
        </a>
    </div>

    <!-- Tabla de últimas ventas -->
    <div class="bg-[#202022] border border-gray-800 rounded-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-800">
            <h3 class="text-sm font-bold text-gray-100 uppercase tracking-wider">Últimas Ventas Realizadas</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-[#161618] text-gray-400 text-xs uppercase font-semibold border-b border-gray-800">
                    <tr>
                        <th class="px-5 py-3">Nro. Nota</th>
                        <th class="px-5 py-3">Cliente</th>
                        <th class="px-5 py-3">Monto</th>
                        <th class="px-5 py-3">Método de Pago</th>
                        <th class="px-5 py-3">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800 text-gray-300">
                    @forelse ($ultimasVentas as $venta)
                        <tr class="hover:bg-[#262629] transition-colors">
                            <td class="px-5 py-3 whitespace-nowrap">
                                <a href="{{ route('ventas.detalle', $venta->nroNotaVenta) }}" wire:navigate
                                   class="font-mono text-cyan-400 hover:underline">#{{ $venta->nroNotaVenta }}</a>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap text-gray-100">
                                {{ $venta->cliente?->nombre }} {{ $venta->cliente?->apellido }}
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap font-mono font-medium text-gray-100">
                                Bs. {{ number_format($venta->total, 2) }}
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold bg-cyan-400/10 text-cyan-400 border border-cyan-400/20">
                                    {{ $venta->pago?->tipoPago ?? '—' }}
                                </span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap text-gray-500">{{ $venta->fecha }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <svg class="w-8 h-8 text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.895-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    No tienes ventas registradas aún.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</x-layouts.app>