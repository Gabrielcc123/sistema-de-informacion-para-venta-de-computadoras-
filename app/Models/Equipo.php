<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;

    protected $table = 'equipo';
    protected $primaryKey = 'idEquipo';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'marca',
        'modelo',
        'numeroSerie',
        'descripcion',
        'gama',
        'estado',
    ];

    protected $attributes = [
        'estado' => 'Recibido',
    ];

    public function ordenes()
    {
        return $this->hasMany(Orden::class, 'idEquipo', 'idEquipo');
    }
}