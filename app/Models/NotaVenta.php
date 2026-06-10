<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaVenta extends Model
{
    use HasFactory;

    protected $table = 'notaVenta';
    protected $primaryKey = 'nroNotaVenta';

    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'idCliente',
        'idPago',
'idUsuario',
        'fecha',
        'total',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente', 'idCliente');
    }

    public function pago()
    {
        return $this->belongsTo(Pago::class, 'idPago', 'idPago');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }

    public function detalles()
    {
        // hasMany(Modelo, llave_foranea_en_destino, llave_primaria_local)
        return $this->hasMany(DetalleVenta::class, 'nroNotaVenta', 'nroNotaVenta');
    }
}
