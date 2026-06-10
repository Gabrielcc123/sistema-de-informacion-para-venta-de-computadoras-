<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuario';
    protected $primaryKey = 'idUsuario';

    protected $fillable = [
        'nombre',
        'apellido',
        'email',       // <-- AGREGAR AQUÍ
        'password',
        'telefono',
        'estado',
        'tipoAssesor',
        'tipoSupervisor',
        'tipoTecnico',
    ];

    protected $hidden = [
        'password',
    ];

    
    /**
 * Determina si el usuario tiene un rol específico o un conjunto de roles.
 */
public function hasRole(string|array $roles): bool
{
    // Si pasamos un array de roles, verificar si cumple con al menos uno
    if (is_array($roles)) {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }
        return false;
    }

    // Normalizar a minúsculas para evitar problemas de escritura
    return match (strtolower($roles)) {
        'administrador', 'supervisor' => (bool) $this->tipoSupervisor,
        'vendedor', 'asesor'          => (bool) $this->tipoAssesor,
        'técnico', 'tecnico'          => (bool) $this->tipoTecnico,
        default                       => false,
    };
}
}