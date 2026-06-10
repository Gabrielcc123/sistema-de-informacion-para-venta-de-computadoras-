<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'producto';
    protected $primaryKey = 'idProducto';
    
    // CRITICAL: Al ser herencia, la PK no se autogenera en esta tabla
    public $incrementing = false; 
    protected $keyType = 'int';

    protected $fillable = [
        'idProducto', // Se debe pasar manualmente el ID del padre al crearlo
        'stock',
        'marca',
        'modelo',
        'numeroSerie'
    ];

    /**
     * Relación Uno a Uno Inversa: Un Producto físico es la especialización de un ProductoServicio
     */
    public function datosGenerales()
    {
        return $this->belongsTo(ProductoServicio::class, 'idProducto', 'idProductoServicio');
    }
}