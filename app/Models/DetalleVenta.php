<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;

    protected $table = 'detalleVenta';
    protected $primaryKey = 'idDetalleVenta';

    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'nroNotaVenta',
        'idProductoServicio',
        'cantidad',
        'precioUnitario',
        'subTotal',
    ];

    public function notaVenta()
    {
        return $this->belongsTo(NotaVenta::class, 'nroNotaVenta', 'nroNotaVenta');
    }

    public function productoServicio()
    {
        return $this->belongsTo(ProductoServicio::class, 'idProductoServicio', 'idProductoServicio');
    }
}
