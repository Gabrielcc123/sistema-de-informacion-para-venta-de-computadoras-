<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Bitacora;
use App\Models\Usuario;

new #[Layout('components.layouts.app')] class extends Component {
    use WithPagination;

    public string $search = '';
    public string $idUsuarioFiltro = '';
    public string $fechaInicio = '';
    public string $fechaFin = '';

    public function with(): array
    {
        $query = Bitacora::with('usuario')
            ->when($this->search, fn($q) => $q->where('accion', 'like', "%{$this->search}%"))
            ->when($this->idUsuarioFiltro, fn($q) => $q->where('idUsuario', $this->idUsuarioFiltro))
            ->when($this->fechaInicio, fn($q) => $q->whereDate('fecha', '>=', $this->fechaInicio))
            ->when($this->fechaFin, fn($q) => $q->whereDate('fecha', '<=', $this->fechaFin))
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc');

        return [
            'registros' => $query->paginate(20),
            'usuarios' => Usuario::orderBy('nombre')->get(),
        ];
    }

    public function updating($property): void
    {
        if (in_array($property, ['search', 'idUsuarioFiltro', 'fechaInicio', 'fechaFin'])) {
            $this->resetPage();
        }
    }
};
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-100">Bitácora de Seguridad</h2>
            <p class="text-sm text-gray-400 mt-1">Auditoría histórica de accesos y movimientos en el sistema.</p>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-[#202022] border border-gray-800 rounded-xl p-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-1">Buscar acción</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Filtrar por acción..."
                           class="block w-full pl-9 pr-3 py-2 border border-gray-700 rounded-lg bg-[#161618] text-gray-100 placeholder-gray-600 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 text-sm transition-all">
                </div>
            </div>

            <div>
                <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-1">Usuario</label>
                <select wire:model.live="idUsuarioFiltro"
                        class="w-full bg-[#161618] border border-gray-700 text-gray-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition-all">
                    <option value="">Todos los usuarios</option>
                    @foreach ($usuarios as $u)
                        <option value="{{ $u->idUsuario }}">{{ $u->nombre }} {{ $u->apellido }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-1">Fecha inicio</label>
                <input wire:model.live="fechaInicio" type="date"
                       class="w-full bg-[#161618] border border-gray-700 text-gray-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition-all">
            </div>

            <div>
                <label class="block text-xs font-mono text-gray-400 uppercase tracking-wider mb-1">Fecha fin</label>
                <input wire:model.live="fechaFin" type="date"
                       class="w-full bg-[#161618] border border-gray-700 text-gray-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition-all">
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="bg-[#202022] border border-gray-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm min-w-[700px]">
                <thead class="bg-[#161618] text-gray-400 text-xs uppercase font-semibold border-b border-gray-800">
                    <tr>
                        <th class="px-5 py-3">Fecha</th>
                        <th class="px-5 py-3">Hora</th>
                        <th class="px-5 py-3">Usuario</th>
                        <th class="px-5 py-3">Acción</th>
                        <th class="px-5 py-3">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800 text-gray-300">
                    @forelse ($registros as $registro)
                        <tr class="hover:bg-[#262629] transition-colors">
                            <td class="px-5 py-3 whitespace-nowrap text-gray-100 text-sm">{{ $registro->fecha }}</td>
                            <td class="px-5 py-3 whitespace-nowrap font-mono text-xs text-gray-500">{{ $registro->hora }}</td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-cyan-400/10 text-cyan-400 flex items-center justify-center font-bold text-xs border border-cyan-400/30">
                                        {{ strtoupper(substr($registro->usuario->nombre ?? 'S', 0, 1) . substr($registro->usuario->apellido ?? 'I', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-100">{{ $registro->usuario->nombre ?? 'Sistema' }} {{ $registro->usuario->apellido ?? '' }}</div>
                                        <div class="text-xs text-gray-500">{{ $registro->usuario->email ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                @php
                                    $accion = strtolower($registro->accion);
                                    $color = match(true) {
                                        str_contains($accion, 'login') && str_contains($accion, 'exitoso') => 'bg-emerald-400/10 text-emerald-400 border-emerald-400/20',
                                        str_contains($accion, 'cierre') || str_contains($accion, 'logout') => 'bg-gray-400/10 text-gray-400 border-gray-400/20',
                                        str_contains($accion, 'bloqueo') => 'bg-amber-400/10 text-amber-400 border-amber-400/20',
                                        str_contains($accion, 'eliminado') || str_contains($accion, 'suspendida') => 'bg-red-400/10 text-red-400 border-red-400/20',
                                        str_contains($accion, 'creado') || str_contains($accion, 'registrad') => 'bg-cyan-400/10 text-cyan-400 border-cyan-400/20',
                                        str_contains($accion, 'editado') || str_contains($accion, 'actualizad') => 'bg-blue-400/10 text-blue-400 border-blue-400/20',
                                        default => 'bg-cyan-400/10 text-cyan-400 border-cyan-400/20',
                                    };
                                @endphp
                                <span class="inline-flex px-2 py-1 text-[11px] font-semibold rounded border {{ $color }}">
                                    {{ $registro->accion }}
                                </span>
                            </td>
                            <td class="px-5 py-3 whitespace-nowrap font-mono text-xs text-gray-500">{{ $registro->ip }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <svg class="w-8 h-8 text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    No se encontraron registros con los filtros aplicados.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($registros->hasPages())
            <div class="px-5 py-4 border-t border-gray-800 bg-[#161618]">
                {{ $registros->links(data: ['scrollTo' => false]) }}
            </div>
        @endif
    </div>
</div>