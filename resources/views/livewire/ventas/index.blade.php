<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\NotaVenta;
use Illuminate\Support\Facades\Auth;

new #[Layout('components.layouts.app')] class extends Component {
    use WithPagination;

    public function with(): array
    {
        $query = NotaVenta::with(['cliente', 'pago', 'usuario'])
            ->orderBy('nroNotaVenta', 'desc');

        if (!Auth::user()->tipoSupervisor) {
            $query->where('idUsuario', Auth::id());
        }

        return [
            'ventas' => $query->paginate(10),
        ];
    }
};
?>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-100">Gestión de Ventas</h2>
            <p class="text-sm text-gray-400 mt-1">Historial de notas de venta registradas en el sistema.</p>
        </div>
        <a href="{{ route('ventas.crear') }}" wire:navigate
           class="inline-flex items-center gap-2 px-4 py-2 bg-cyan-400 text-black text-sm font-bold rounded-lg neon-glow hover:bg-cyan-300 transition-all duration-200">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Nueva Venta
        </a>
    </div>

    <!-- Tabla -->
    <div class="bg-[#202022] border border-gray-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm min-w-[900px]">
                <thead class="bg-[#161618] text-gray-400 text-xs uppercase font-semibold border-b border-gray-800">
                    <tr>
                        <th class="px-5 py-3">Nro. Nota</th>
                        <th class="px-5 py-3">Fecha</th>
                        <th class="px-5 py-3">Cliente</th>
                        <th class="px-5 py-3">Asesor</th>
                        <th class="px-5 py-3">Método de Pago</th>
                        <th class="px-5 py-3">Total</th>
                        <th class="px-5 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800 text-gray-300">
                    @forelse ($ventas as $venta)
                        <tr class="hover:bg-[#262629] transition-colors">
                            <td class="px-5 py-3 whitespace-nowrap font-mono text-gray-100">#{{ $venta->nroNotaVenta }}</td>
                            <td class="px-5 py-3 whitespace-nowrap">{{ $venta->fecha }}</td>
                            <td class="px-5 py-3 whitespace-nowrap text-gray-100">
                                {{ $venta->cliente?->nombre }} {{ $venta->cliente?->apellido }}
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                {{ $venta->usuario?->nombre }} {{ $venta->usuario?->apellido }}
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold bg-cyan-400/10 text-cyan-400 border border-cyan-400/20">
                                    {{ $venta->pago?->tipoPago ?? '—' }}
                                </span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap font-mono font-bold text-gray-100">
                                Bs. {{ number_format($venta->total, 2) }}
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap text-right">
                                <a href="{{ route('ventas.detalle', $venta->nroNotaVenta) }}" wire:navigate
                                   class="inline-flex p-1.5 rounded border border-gray-700 text-gray-400 hover:text-cyan-400 hover:border-cyan-400/30 transition-all"
                                   title="Ver detalle">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <svg class="w-8 h-8 text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.895-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    No hay ventas registradas.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($ventas->hasPages())
            <div class="px-5 py-4 border-t border-gray-800 bg-[#161618]">
                {{ $ventas->links(data: ['scrollTo' => false]) }}
            </div>
        @endif
    </div>
</div>
