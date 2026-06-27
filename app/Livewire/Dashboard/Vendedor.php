<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\NotaVenta;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Vendedor extends Component
{
    public int $ventasHoy = 0;
    public float $montoHoy = 0;
    public int $clientesAtendidos = 0;

    public $ultimasVentas = [];

    public function mount(): void
    {
        $hoy = Carbon::today()->toDateString();
        $idActual = Auth::id();

        $this->ventasHoy = NotaVenta::where('idUsuario', $idActual)
            ->whereDate('fecha', $hoy)
            ->count();

        $this->montoHoy = (float) (NotaVenta::where('idUsuario', $idActual)
            ->whereDate('fecha', $hoy)
            ->sum('total') ?? 0);

        $this->clientesAtendidos = NotaVenta::where('idUsuario', $idActual)
            ->whereDate('fecha', $hoy)
            ->distinct('idCliente')
            ->count('idCliente');

        $this->ultimasVentas = NotaVenta::with(['cliente', 'pago'])
            ->where('idUsuario', $idActual)
            ->orderBy('nroNotaVenta', 'desc')
            ->limit(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard.vendedor');
    }
}
