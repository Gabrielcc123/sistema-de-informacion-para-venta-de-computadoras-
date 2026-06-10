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

new #[Layout('components.layouts.app')] class extends Component {
    public ?int $idCliente = null;
    public ?int $idPago = null;
    public string $productoSeleccionado = '';

    public array $items = [];
    public float $total = 0;
    public bool $ventaEnProceso = false;

    public ?int $idEquipo = null;
    public ?int $idTecnico = null;
    public bool $requiereOrden = false;

    public function with(): array
    {
        return [
            'clientes' => Cliente::orderBy('apellido')->orderBy('nombre')->get(),
            'pagos' => Pago::orderBy('tipoPago')->get(),
            'productos' => ProductoServicio::orderBy('nombre')->get(),
            'equipos' => Equipo::orderBy('idEquipo', 'desc')->get(),
            'tecnicos' => Usuario::where('tipoTecnico', 1)->where('estado', 1)->get(),
        ];
    }

    public function agregarItem(): void
    {
        if (empty($this->productoSeleccionado)) {
            $this->addError('productoSeleccionado', 'Seleccione un producto o servicio.');
            return;
        }

        $idProductoServicio = (int) $this->productoSeleccionado;

        $existe = collect($this->items)->firstWhere('id', $idProductoServicio);
        if ($existe) {
            $this->dispatch('notify', message: 'El producto ya está en el carrito.', type: 'error');
            return;
        }

        $ps = ProductoServicio::find($idProductoServicio);
        if (!$ps) {
            $this->dispatch('notify', message: 'Producto no encontrado.', type: 'error');
            return;
        }

        if ($ps->tipo === 'Producto') {
            $producto = Producto::where('idProducto', $ps->idProductoServicio)->first();
            if (!$producto || $producto->stock <= 0) {
                $this->dispatch('notify', message: 'Producto sin stock disponible.', type: 'error');
                return;
            }
        }

        $this->items[] = [
            'id' => $ps->idProductoServicio,
            'nombre' => $ps->nombre,
            'cantidad' => 1,
            'precioUnitario' => (float) $ps->precioUnitario,
            'subtotal' => (float) $ps->precioUnitario,
            'tipo' => $ps->tipo,
        ];

        $this->reset('productoSeleccionado');
        $this->calcularTotal();
    }

    public function eliminarItem(int $index): void
    {
        if (isset($this->items[$index])) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
        }
        $this->calcularTotal();
    }

    public function calcularTotal(): void
    {
        foreach ($this->items as $index => $item) {
            $this->items[$index]['subtotal'] = $item['cantidad'] * $item['precioUnitario'];
        }
        $this->total = collect($this->items)->sum('subtotal');
        $this->requiereOrden = collect($this->items)->contains('tipo', 'Servicio');
    }

    public function guardar(): void
    {
        if ($this->ventaEnProceso) {
            return;
        }
        $this->ventaEnProceso = true;

        $rules = [
            'idCliente' => ['required', 'integer', 'exists:cliente,idCliente'],
            'idPago'    => ['required', 'integer', 'exists:pago,idPago'],
            'items'     => ['required', 'array', 'min:1'],
            'items.*.cantidad' => ['required', 'integer', 'min:1'],
        ];

        $messages = [
            'idCliente.required' => 'Debe seleccionar un cliente.',
            'idPago.required'    => 'Debe seleccionar un método de pago.',
            'items.required'     => 'Debe agregar al menos un producto o servicio.',
            'items.min'          => 'Debe agregar al menos un producto o servicio.',
        ];

        if ($this->requiereOrden) {
            $rules['idEquipo'] = ['required', 'integer', 'exists:equipo,idEquipo'];
            $rules['idTecnico'] = ['required', 'integer', 'exists:usuario,idUsuario'];
            $messages['idEquipo.required'] = 'Debe seleccionar un equipo para la orden de servicio.';
            $messages['idTecnico.required'] = 'Debe asignar un técnico para la orden de servicio.';
        }

        $this->validate($rules, $messages);

        DB::transaction(function () {
            $nota = NotaVenta::create([
                'idCliente' => $this->idCliente,
                'idPago'    => $this->idPago,
                'idUsuario' => Auth::id(),
                'fecha'     => now()->toDateString(),
                'total'     => $this->total,
            ]);

            foreach ($this->items as $item) {
                DetalleVenta::create([
                    'nroNotaVenta'        => $nota->nroNotaVenta,
                    'idProductoServicio' => $item['id'],
                    'cantidad'           => $item['cantidad'],
                    'precioUnitario'     => $item['precioUnitario'],
                    'subTotal'           => $item['subtotal'],
                ]);

                if ($item['tipo'] === 'Producto') {
                    Producto::where('idProducto', $item['id'])->decrement('stock', $item['cantidad']);
                }
            }

            if ($this->requiereOrden) {
                Orden::create([
                    'nroNotaVenta' => $nota->nroNotaVenta,
                    'idEquipo'     => $this->idEquipo,
                    'idTecnico'    => $this->idTecnico,
                    'estado'       => 'Pendiente',
                ]);
                Equipo::where('idEquipo', $this->idEquipo)->update(['estado' => 'En Diagnóstico']);
            }
        });

        Bitacora::registrar('Venta registrada. Total: Bs ' . number_format($this->total, 2), Auth::id());
        $this->dispatch('notify', message: 'Venta registrada correctamente.', type: 'success');

        $this->redirectRoute('ventas.index', navigate: true);
    }

    public function cancelar(): void
    {
        $this->redirectRoute('ventas.index', navigate: true);
    }
};
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
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->idCliente }}">{{ $cliente->apellido }} {{ $cliente->nombre }} (CI: {{ $cliente->ci }})</option>
                        @endforeach
                    </select>
                    @error('idCliente') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Método de Pago <span class="text-red-400">*</span></label>
                    <select wire:model="idPago"
                            class="w-full bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all">
                        <option value="" class="text-gray-500">Seleccione método</option>
                        @foreach ($pagos as $pago)
                            <option value="{{ $pago->idPago }}">{{ $pago->tipoPago }}</option>
                        @endforeach
                    </select>
                    @error('idPago') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="pt-2 border-t border-gray-800/50">
                    <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Agregar Ítem</label>
                    <div class="flex gap-2">
                        <select wire:model="productoSeleccionado"
                                class="flex-1 bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all">
                            <option value="" class="text-gray-500">Seleccione producto o servicio</option>
                            @foreach ($productos as $prod)
                                <option value="{{ $prod->idProductoServicio }}">{{ $prod->nombre }} — Bs. {{ number_format($prod->precioUnitario, 2) }}</option>
                            @endforeach
                        </select>
                        <button type="button" wire:click="agregarItem"
                                class="px-3 py-2 bg-cyan-400 text-black rounded-lg hover:bg-cyan-300 transition-all font-bold text-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </button>
                    </div>
                    @error('productoSeleccionado') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            @if($requiereOrden)
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
                            @foreach ($equipos as $eq)
                                <option value="{{ $eq->idEquipo }}">#{{ $eq->idEquipo }} — {{ $eq->marca ?? 'S/M' }} {{ $eq->modelo ?? '' }} {{ $eq->numeroSerie ? '(S/N: ' . $eq->numeroSerie . ')' : '' }}</option>
                            @endforeach
                        </select>
                        @error('idEquipo') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-2">Asignar Técnico <span class="text-red-400">*</span></label>
                        <select wire:model="idTecnico"
                                class="w-full bg-[#161618] border border-cyan-400/30 text-gray-100 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all">
                            <option value="" class="text-gray-500">Seleccione un técnico</option>
                            @foreach ($tecnicos as $tec)
                                <option value="{{ $tec->idUsuario }}">{{ $tec->nombre }} {{ $tec->apellido }}</option>
                            @endforeach
                        </select>
                        @error('idTecnico') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            @endif

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
                    <span class="text-xs text-gray-500">{{ count($items) }} ítem(s)</span>
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
                            @forelse ($items as $index => $item)
                                <tr class="hover:bg-[#262629] transition-colors">
                                    <td class="px-5 py-3 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            @if ($item['tipo'] === 'Servicio')
                                                <span class="inline-flex px-1.5 py-0.5 rounded text-[10px] font-semibold bg-purple-400/10 text-purple-400 border border-purple-400/20">SVC</span>
                                            @else
                                                <span class="inline-flex px-1.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-400/10 text-emerald-400 border border-emerald-400/20">PROD</span>
                                            @endif
                                            <span class="text-gray-100">{{ $item['nombre'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3 whitespace-nowrap">
                                        <input type="number" min="1"
                                               wire:model="items.{{ $index }}.cantidad"
                                               wire:change="calcularTotal"
                                               class="w-20 bg-[#161618] border border-gray-800 text-gray-100 rounded-lg px-2 py-1.5 text-sm text-center focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/50 transition-all">
                                    </td>
                                    <td class="px-5 py-3 whitespace-nowrap font-mono text-gray-100">
                                        Bs. {{ number_format($item['precioUnitario'], 2) }}
                                    </td>
                                    <td class="px-5 py-3 whitespace-nowrap font-mono font-bold text-cyan-400">
                                        Bs. {{ number_format($item['subtotal'], 2) }}
                                    </td>
                                    <td class="px-5 py-3 whitespace-nowrap text-right">
                                        <button type="button" wire:click="eliminarItem({{ $index }})"
                                                class="p-1.5 rounded border border-red-400/30 text-red-400 hover:bg-red-400/10 transition-all">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
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
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Total y Confirmar -->
            <div class="bg-[#202022] border border-gray-800 rounded-xl p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-center sm:text-left">
                    <p class="text-xs font-mono text-gray-400 uppercase tracking-wider">Total a Pagar</p>
                    <p class="text-3xl font-bold text-cyan-400">Bs. {{ number_format($total, 2) }}</p>
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
</div>