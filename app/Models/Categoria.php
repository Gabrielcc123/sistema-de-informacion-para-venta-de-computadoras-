<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    // 1. Nombre de la tabla en la base de datos
    protected $table = 'categoria';

    // 2. Definición de la clave primaria personalizada
    protected $primaryKey = 'idCategoria';

    // 3. Como es autoincremental, confirmamos que es de tipo entero
    public $incrementing = true;
    protected $keyType = 'int';

    // 4. Campos habilitados para asignación masiva (Mass Assignment)
    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    /**
     * Relación Uno a Muchos con ProductoServicio
     * Una categoría contiene muchos productos o servicios de IrisComputer
     */
    public function productosServicios()
    {
        return $this->hasMany(ProductoServicio::class, 'idCategoria', 'idCategoria');
    }
}
