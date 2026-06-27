<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Orden;
use Illuminate\Support\Facades\Auth;

class Tecnico extends Component
{
    public int $asignadas = 0;
    public int $enProceso = 0;
    public int $finalizadas = 0;

    public function mount(): void
    {
        $idTecnico = Auth::id();

        $this->asignadas = Orden::where('idTecnico', $idTecnico)
            ->where('estado', 'Pendiente')
            ->count();

        $this->enProceso = Orden::where('idTecnico', $idTecnico)
            ->whereIn('estado', ['En diagnóstico', 'En reparación'])
            ->count();

        $this->finalizadas = Orden::where('idTecnico', $idTecnico)
            ->where('estado', 'Finalizado')
            ->count();
    }

    public function render()
    {
        return view('livewire.dashboard.tecnico');
    }
}
