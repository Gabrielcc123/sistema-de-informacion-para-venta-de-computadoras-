<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

class Bitacora extends Model
{
    // 1. Forzamos el nombre exacto de la tabla (en singular)
    protected $table = 'bitacora';

    // 2. Apagamos las columnas automáticas created_at y updated_at
    public $timestamps = false;

    // 3. (Opcional pero recomendado) Si tu llave primaria es idBitacora en lugar de id, descomenta esto:
    // protected $primaryKey = 'idBitacora';

    protected $fillable = [
        'idUsuario',
        'accion',
        'ip',
        'fecha',
        'hora'
    ];

    /**
     * Método global para registrar en la bitácora con una sola línea de código.
     */
    public static function registrar(string $accion, $idUsuario = null): void
    {
        self::create([
            'idUsuario' => $idUsuario ?? Auth::id(),
            'accion'    => $accion,
            'ip'        => Request::ip(),
            'fecha'     => now()->toDateString(), // Formato: YYYY-MM-DD
            'hora'      => now()->toTimeString(), // Formato: HH:MM:SS
        ]);
    }

    /**
     * Relación: Una bitácora pertenece a un usuario.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario');
    }
}