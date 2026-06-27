<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\NotaVenta;
use App\Models\Orden;
use App\Models\Usuario;
use App\Models\Bitacora;
use Illuminate\Support\Carbon;

class Admin extends Component
{
    // Métricas del grid (inicializadas en 0 para que la vista cargue sin errores)
    public float $ventasHoy = 0;
    public int $ordenesPendientes = 0;
    public int $usuariosActivos = 0;
    public int $accionesHoy = 0;

    // Datos para los gráficos
    public array $ventasSemana = [];
    public array $pagosDistribucion = [];

    // Acciones recientes para la tabla
    public $acciones = [];

    public function mount(): void
    {
        $hoy = now()->toDateString();

        // 1. Ventas totales del día
        $this->ventasHoy = (float) (NotaVenta::where('fecha', $hoy)->sum('total') ?? 0);

        // 2. Órdenes pendientes
        $this->ordenesPendientes = Orden::where('estado', 'Pendiente')->count();

        // 3. Usuarios activos
        $this->usuariosActivos = Usuario::where('estado', 1)->count();

        // 4. Acciones registradas hoy en bitácora
        $this->accionesHoy = Bitacora::whereDate('fecha', $hoy)->count();

        // 5. Ventas de los últimos 7 días para el gráfico de barras
        for ($i = 6; $i >= 0; $i--) {
            $fecha = now()->subDays($i)->toDateString();
            $label = now()->subDays($i)->translatedFormat('D');
            $this->ventasSemana[$label] = round((float) (NotaVenta::where('fecha', $fecha)->sum('total') ?? 0), 2);
        }

        // 6. Distribución de métodos de pago para el gráfico doughnut
        $this->pagosDistribucion = NotaVenta::selectRaw('idPago, COUNT(*) as total')
            ->groupBy('idPago')
            ->with('pago')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->pago->tipoPago ?? 'N/A' => $item->total];
            })
            ->toArray();

        // 7. Últimas 10 acciones para la tabla
        $this->acciones = Bitacora::with('usuario')
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->limit(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard.admin');
    }
}
