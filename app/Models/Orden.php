<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    use HasFactory;

    protected $table = 'orden';
    protected $primaryKey = 'idOrden';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nroNotaVenta',
        'idEquipo',
        'idTecnico',
        'estado',
    ];

    const ESTADOS = [
        'Pendiente',
        'En diagnóstico',
        'En reparación',
        'Finalizado',
    ];

    protected $attributes = [
        'estado' => 'Pendiente',
    ];

    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'idEquipo', 'idEquipo');
    }

    public function notaVenta()
    {
        return $this->belongsTo(NotaVenta::class, 'nroNotaVenta', 'nroNotaVenta');
    }

    public function tecnico()
    {
        return $this->belongsTo(Usuario::class, 'idTecnico', 'idUsuario');
    }
}