<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    use HasFactory;

    protected $table = 'permiso';
    protected $primaryKey = 'idPermiso';

    public $incrementing = true;
    protected $keyType = 'int';

    // Desactivamos los timestamps automáticos
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    /**
     * Relación Muchos a Muchos: Un permiso pertenece a muchos Roles
     */
    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'rolpermiso', 'idPermiso', 'idRol');
    }
}