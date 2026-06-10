<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'rol';
    protected $primaryKey = 'idRol';

    public $incrementing = true;
    protected $keyType = 'int';

    // Desactivamos los timestamps automáticos
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    /**
     * Relación Muchos a Muchos: Un Rol tiene asignados muchos Permisos
     */
    public function permisos()
    {
        return $this->belongsToMany(Permiso::class, 'rolpermiso', 'idRol', 'idPermiso');
    }
}
