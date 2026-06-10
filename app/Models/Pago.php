<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pago';
    protected $primaryKey = 'idPago';

    public $incrementing = true;
    protected $keyType = 'int';

    // Desactivamos los timestamps ya que la migración no los incluye
    public $timestamps = false;

    protected $fillable = [
        'tipoPago',
        'descripcion'
    ];

    /**
     * Relación Uno a Muchos: Un tipo de pago puede estar referenciado en muchas notas de venta
     */
    public function notasVenta()
    {
        return $this->hasMany(NotaVenta::class, 'idPago', 'idPago');
    }
}
