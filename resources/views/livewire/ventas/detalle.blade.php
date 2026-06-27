<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\NotaVenta;

new #[Layout('components.layouts.app')] class extends Component {
    public $venta;

    public function mount($id): void
    {
        $this->venta = NotaVenta::with([
            'cliente',
            'pago',
            'usuario',
            'detalles.productoServicio',
        ])->findOrFail($id);
    }
};
?>

<div class="space-y-6">
    <!-- Acciones -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 print:hidden">
        <a href="{{ route('ventas.index') }}" wire:navigate
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-300 bg-gray-800 border border-gray-700 rounded-lg hover:bg-gray-700 transition-all print-hidden">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Volver al Listado
        </a>

        <button type="button" onclick="window.print()"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-bold text-black bg-cyan-400 rounded-lg hover:bg-cyan-300 neon-glow transition-all print-hidden">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m-5.95-10.828c.24.03.48.062.72.096M11.05 7.172c.24.03.48.062.72.096M11.05 7.172L10.34 18m7.56-10.828c.24.03.48.062.72.096M17.9 7.172L17.66 18M6.72 13.829a42.415 42.415 0 0010.56 0" />
            </svg>
            Imprimir / Guardar PDF
        </button>
    </div>

    <!-- Recibo / Nota de Venta -->
    <div id="recibo-imprimir" class="bg-[#202022] border border-gray-800 rounded-xl p-8 max-w-4xl mx-auto">
        <!-- Cabecera del recibo -->
        <div class="text-center border-b border-gray-800 pb-6 mb-6">
            <div class="flex flex-col items-center justify-center gap-2 mb-2">
                <img src="{{ asset('img/logoP.png') }}" alt="Logo Syscraft" class="w-12 h-12 object-contain">
                <span class="text-xl font-bold text-gray-100 tracking-wider">SYSCRAFT</span>
            </div>
            <p class="text-xs text-gray-500 uppercase tracking-widest">ERP - Sistema de Gestión Comercial</p>
            <h1 class="text-2xl font-bold text-gray-100 mt-4">NOTA DE VENTA</h1>
            <p class="text-sm text-gray-400 mt-1">Nro. <span class="font-mono font-bold text-cyan-400">#{{ $venta->nroNotaVenta }}</span></p>
        </div>

        <!-- Datos generales -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="space-y-2">
                <p class="text-xs font-mono text-gray-500 uppercase tracking-wider">Fecha de Emisión</p>
                <p class="text-sm text-gray-100 font-medium">{{ $venta->fecha }}</p>
            </div>
            <div class="space-y-2">
                <p class="text-xs font-mono text-gray-500 uppercase tracking-wider">Método de Pago</p>
                <p class="text-sm text-gray-100 font-medium">{{ $venta->pago?->tipoPago ?? '—' }}</p>
            </div>
            <div class="space-y-2">
                <p class="text-xs font-mono text-gray-500 uppercase tracking-wider">Asesor / Vendedor</p>
                <p class="text-sm text-gray-100 font-medium">
                    {{ $venta->usuario?->nombre }} {{ $venta->usuario?->apellido }}
                </p>
            </div>
        </div>

        <!-- Datos del cliente -->
        <div class="bg-[#161618] border border-gray-800 rounded-lg p-5 mb-6">
            <p class="text-xs font-mono text-cyan-400 uppercase tracking-wider mb-3">Datos del Cliente</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-500">Nombre completo:</span>
                    <span class="text-gray-100 ml-1 font-medium">
                        {{ $venta->cliente?->nombre }} {{ $venta->cliente?->apellido }}
                    </span>
                </div>
                <div>
                    <span class="text-gray-500">CI:</span>
                    <span class="text-gray-100 ml-1 font-mono">{{ $venta->cliente?->ci ?? '—' }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Teléfono:</span>
                    <span class="text-gray-100 ml-1">{{ $venta->cliente?->telefono ?? '—' }}</span>
                </div>
            </div>
        </div>

        <!-- Tabla de detalles -->
        <div class="overflow-x-auto mb-6">
            <table class="w-full text-left text-sm">
                <thead class="bg-[#161618] text-gray-400 text-xs uppercase font-semibold border-b border-gray-800">
                    <tr>
                        <th class="px-4 py-3 w-16">Cant.</th>
                        <th class="px-4 py-3">Descripción</th>
                        <th class="px-4 py-3 text-right">Precio Unit.</th>
                        <th class="px-4 py-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800 text-gray-300">
                    @foreach ($venta->detalles as $detalle)
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap text-center font-mono text-gray-100">{{ $detalle->cantidad }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    @if ($detalle->productoServicio?->tipo === 'Servicio')
                                        <span class="inline-flex px-1.5 py-0.5 rounded text-[10px] font-semibold bg-purple-400/10 text-purple-400 border border-purple-400/20">SVC</span>
                                    @else
                                        <span class="inline-flex px-1.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-400/10 text-emerald-400 border border-emerald-400/20">PROD</span>
                                    @endif
                                    <span class="text-gray-100">{{ $detalle->productoServicio?->nombre ?? '—' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right font-mono text-gray-100">
                                Bs. {{ number_format($detalle->precioUnitario, 2) }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right font-mono font-bold text-cyan-400">
                                Bs. {{ number_format($detalle->subTotal, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Total -->
        <div class="flex items-center justify-end border-t border-gray-800 pt-6">
            <div class="text-right">
                <p class="text-xs font-mono text-gray-500 uppercase tracking-wider mb-1">Total General</p>
                <p class="text-4xl font-bold text-cyan-400">Bs. {{ number_format($venta->total, 2) }}</p>
            </div>
        </div>

        <!-- Pie del recibo -->
        <div class="mt-8 pt-6 border-t border-gray-800 text-center">
            <p class="text-xs text-gray-600">Gracias por su preferencia. Este documento es una representación digital de su compra.</p>
            <p class="text-xs text-gray-600 mt-1">Iris Computer — Venta de Componentes y Servicios Técnicos</p>
        </div>
    </div>

    <style>
        @media print {
            body * { visibility: hidden; }
            #recibo-imprimir, #recibo-imprimir * { visibility: visible; }
            #recibo-imprimir { position: absolute; left: 0; top: 0; width: 100%; box-shadow: none; background: white; color: black; }
            .print-hidden { display: none !important; }
        }
    </style>
</div>