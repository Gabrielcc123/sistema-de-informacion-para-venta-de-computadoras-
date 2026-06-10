<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'cliente';
    protected $primaryKey = 'idCliente';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'ci',
        'nombre',
        'apellido',
        'telefono'
    ];

    /**
     * Relación Uno a Muchos: Un cliente puede registrar múltiples compras / notas de venta
     */
    public function notasVenta()
    {
        return $this->hasMany(NotaVenta::class, 'idCliente', 'idCliente');
    }
}
