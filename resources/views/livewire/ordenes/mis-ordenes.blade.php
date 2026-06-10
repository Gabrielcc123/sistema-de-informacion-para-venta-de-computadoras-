<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Orden;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;

new #[Layout('components.layouts.app')] class extends Component {
    public function with(): array
    {
        $ordenes = Orden::with(['equipo', 'notaVenta.cliente'])
            ->where('idTecnico', Auth::id())
            ->whereNotIn('estado', ['Entregado'])
            ->orderBy('idOrden', 'desc')
            ->get();

        $hoy = now()->toDateString();
        $asignadas = $ordenes->count();
        $enProceso = $ordenes->whereIn('estado', ['En diagnóstico', 'En reparación'])->count();
        $finalizadasHoy = Orden::where('idTecnico', Auth::id())
            ->where('estado', 'Finalizado')
            ->whereDate('updated_at', $hoy)
            ->count();

        return [
            'ordenes' => $ordenes,
            'asignadas' => $asignadas,
            'enProceso' => $enProceso,
            'finalizadasHoy' => $finalizadasHoy,
        ];
    }

    public function cambiarEstado(int $idOrden, string $nuevoEstado): void
    {
        $orden = Orden::findOrFail($idOrden);
        $orden->update(['estado' => $nuevoEstado]);

        $estadoEquipo = match($nuevoEstado) {
            'En diagnóstico' => 'En Diagnóstico',
            'En reparación' => 'En Reparación',
            'Finalizado' => 'Listo',
            default => null,
        };

        if ($estadoEquipo && $orden->equipo) {
            $orden->equipo->update(['estado' => $estadoEquipo]);
        }

        Bitacora::registrar("Orden #{$idOrden} cambió a estado: {$nuevoEstado}", Auth::id());
        $this->dispatch('notify', message: "Orden #{$idOrden} actualizada a: {$nuevoEstado}", type: 'success');
    }
};
?>

<div class="space-y-6">
    <div class="flex items-center justify-between mb-2">
        <div>
            <h2 class="text-2xl font-bold text-gray-100">Mis Órdenes Asignadas</h2>
            <p class="text-sm text-gray-400 mt-1">Gestiona el avance de tus órdenes de servicio técnico.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-[#202022] border border-gray-800 rounded-xl p-5 relative overflow-hidden group hover:border-gray-700 transition-all">
            <div class="absolute top-0 right-0 w-24 h-24 bg-amber-400/5 rounded-bl-full -mr-4 -mt-4"></div>
            <div class="flex justify-between items-start mb-4">
                <span class="text-xs font-mono text-gray-500 uppercase tracking-wider">Asignadas</span>
                <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.032 4.032m-1.745 1.437l.102.085" />
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-100 mb-1">{{ $asignadas }}</div>
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
            <div class="text-3xl font-bold text-gray-100 mb-1">{{ $enProceso }}</div>
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
            <div class="text-3xl font-bold text-gray-100 mb-1">{{ $finalizadasHoy }}</div>
            <div class="text-xs text-emerald-400">Listas para entrega</div>
        </div>
    </div>

    @php
        $estadoColors = [
            'Pendiente' => 'bg-blue-400/10 text-blue-400 border-blue-400/20',
            'En diagnóstico' => 'bg-amber-400/10 text-amber-400 border-amber-400/20',
            'En reparación' => 'bg-purple-400/10 text-purple-400 border-purple-400/20',
            'Finalizado' => 'bg-emerald-400/10 text-emerald-400 border-emerald-400/20',
        ];

        $estadoSiguiente = [
            'Pendiente' => 'En diagnóstico',
            'En diagnóstico' => 'En reparación',
            'En reparación' => 'Finalizado',
        ];
    @endphp

    <div class="space-y-4">
        @forelse ($ordenes as $orden)
            <div class="bg-[#202022] border border-gray-800 rounded-xl p-5 hover:border-gray-700 transition-all">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                    <div class="flex-1 space-y-3">
                        <div class="flex items-center gap-3">
                            <span class="font-mono text-cyan-400 text-sm font-bold">#{{ $orden->idOrden }}</span>
                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold border {{ $estadoColors[$orden->estado] ?? 'bg-gray-400/10 text-gray-400 border-gray-400/20' }}">
                                {{ $orden->estado }}
                            </span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2 text-sm">
                            <div>
                                <span class="text-gray-500">Equipo:</span>
                                <span class="text-gray-100 ml-1">{{ $orden->equipo?->marca ?? '—' }} {{ $orden->equipo?->modelo ?? '' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Serie:</span>
                                <span class="text-gray-300 font-mono text-xs ml-1">{{ $orden->equipo?->numeroSerie ?? '—' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Cliente:</span>
                                <span class="text-gray-100 ml-1">{{ $orden->notaVenta?->cliente?->nombre ?? '—' }} {{ $orden->notaVenta?->cliente?->apellido ?? '' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Gama:</span>
                                <span class="text-gray-300 ml-1">{{ $orden->equipo?->gama ?? '—' }}</span>
                            </div>
                        </div>
                        @if($orden->equipo?->descripcion)
                            <div class="bg-[#161618] border border-gray-800 rounded-lg px-4 py-2 text-sm">
                                <span class="text-gray-500 text-xs uppercase tracking-wider">Problema:</span>
                                <p class="text-gray-300 mt-1">{{ $orden->equipo->descripcion }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-col gap-2 min-w-[200px]">
                        <label class="text-xs font-mono text-gray-400 uppercase tracking-wider">Avanzar estado</label>
                        @if(isset($estadoSiguiente[$orden->estado]))
                            <button wire:click="cambiarEstado({{ $orden->idOrden }}, '{{ $estadoSiguiente[$orden->estado] }}')"
                                    class="px-4 py-2 text-sm font-bold text-black bg-cyan-400 rounded-lg hover:bg-cyan-300 neon-glow transition-all duration-200">
                                Pasar a: {{ $estadoSiguiente[$orden->estado] }}
                            </button>
                        @elseif($orden->estado === 'Finalizado')
                            <span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-emerald-400 bg-emerald-400/10 border border-emerald-400/20 rounded-lg">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Completada
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-[#202022] border border-gray-800 rounded-xl p-12 text-center">
                <svg class="w-12 h-12 text-gray-600 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.032 4.032m-1.745 1.437l.102.085" />
                </svg>
                <p class="text-gray-400 text-sm">No tienes órdenes asignadas pendientes.</p>
            </div>
        @endforelse
    </div>

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
</div>