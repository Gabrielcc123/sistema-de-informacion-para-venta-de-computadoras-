<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Orden;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;

new #[Layout('components.layouts.app')] class extends Component {
    use WithPagination;

    public string $search = '';

    public function with(): array
    {
        return [
            'ordenes' => Orden::with(['equipo', 'notaVenta.cliente', 'tecnico'])
                ->when($this->search, function ($q) {
                    $q->whereHas('equipo', fn($e) => $e->where('marca', 'like', "%{$this->search}%")->orWhere('modelo', 'like', "%{$this->search}%"))
                      ->orWhereHas('notaVenta.cliente', fn($c) => $c->where('nombre', 'like', "%{$this->search}%")->orWhere('apellido', 'like', "%{$this->search}%"));
                })
                ->orderBy('idOrden', 'desc')
                ->paginate(10),
        ];
    }
};
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-100">Órdenes de Servicio</h2>
            <p class="text-sm text-gray-400 mt-1">Administración de todas las órdenes de soporte técnico.</p>
        </div>
    </div>

    <div class="bg-[#202022] border border-gray-800 rounded-xl px-5 py-4">
        <div class="relative max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por equipo o cliente..."
                   class="block w-full pl-10 pr-3 py-2 border border-gray-700 rounded-lg bg-[#161618] text-gray-100 placeholder-gray-600 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 sm:text-sm transition-all">
        </div>
    </div>

    @php
        $estadoColors = [
            'Pendiente' => 'text-blue-400 border-blue-400/20 bg-blue-400/10',
            'En diagnóstico' => 'text-amber-400 border-amber-400/20 bg-amber-400/10',
            'En reparación' => 'text-purple-400 border-purple-400/20 bg-purple-400/10',
            'Finalizado' => 'text-emerald-400 border-emerald-400/20 bg-emerald-400/10',
        ];
    @endphp

    <div class="bg-[#202022] border border-gray-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm min-w-[900px]">
                <thead class="bg-[#161618] text-gray-400 text-xs uppercase font-semibold border-b border-gray-800">
                    <tr>
                        <th class="px-5 py-3">Orden</th>
                        <th class="px-5 py-3">Cliente</th>
                        <th class="px-5 py-3">Equipo</th>
                        <th class="px-5 py-3">Técnico</th>
                        <th class="px-5 py-3">Estado</th>
                        <th class="px-5 py-3">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800 text-gray-300">
                    @forelse ($ordenes as $orden)
                        <tr class="hover:bg-[#262629] transition-colors">
                            <td class="px-5 py-3 whitespace-nowrap font-mono text-cyan-400 text-xs">#{{ $orden->idOrden }}</td>
                            <td class="px-5 py-3 whitespace-nowrap text-gray-100">{{ $orden->notaVenta?->cliente?->nombre ?? '—' }} {{ $orden->notaVenta?->cliente?->apellido ?? '' }}</td>
                            <td class="px-5 py-3 whitespace-nowrap">{{ $orden->equipo?->marca ?? '—' }} {{ $orden->equipo?->modelo ?? '' }}</td>
                            <td class="px-5 py-3 whitespace-nowrap">{{ $orden->tecnico?->nombre ?? '—' }} {{ $orden->tecnico?->apellido ?? '' }}</td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold border {{ $estadoColors[$orden->estado] ?? 'text-gray-400 border-gray-400/20 bg-gray-400/10' }}">
                                    {{ $orden->estado }}
                                </span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap text-gray-500 text-xs">{{ $orden->created_at?->format('d/m/Y') ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <svg class="w-8 h-8 text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.032 4.032m-1.745 1.437l.102.085" />
                                    </svg>
                                    No hay órdenes de servicio registradas.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($ordenes->hasPages())
            <div class="px-5 py-4 border-t border-gray-800 bg-[#161618]">
                {{ $ordenes->links(data: ['scrollTo' => false]) }}
            </div>
        @endif
    </div>
</div>