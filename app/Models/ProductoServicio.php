<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoServicio extends Model
{
    use HasFactory;

    protected $table = 'productoServicio';
    protected $primaryKey = 'idProductoServicio';
    
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'idCategoria',
        'nombre',
        'precioUnitario',
        'garantia',
        'tipo'
    ];

    /**
     * Relación Inversa: Muchos ProductoServicio pertenecen a una Categoría
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'idCategoria', 'idCategoria');
    }

    /**
     * Relación Uno a Uno con la especialización Producto
     */
    public function productoFisico()
    {
        return $this->hasOne(Producto::class, 'idProducto', 'idProductoServicio');
    }

    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class, 'idProductoServicio', 'idProductoServicio');
    }
}