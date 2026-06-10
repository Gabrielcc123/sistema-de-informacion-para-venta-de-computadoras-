<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;
use App\Models\Permiso;
use Illuminate\Support\Facades\DB;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear los roles principales
        $roles = [
            'Administrador' => 'Acceso total a todos los módulos',
            'Vendedor' => 'Gestión de clientes y emisión de notas de venta',
            'Técnico' => 'Gestión de equipos y órdenes de soporte',
            'Auditor' => 'Monitoreo de acciones a través de la bitácora',
        ];

        foreach ($roles as $nombre => $descripcion) {
            Rol::firstOrCreate(
                ['nombre' => $nombre],
                ['descripcion' => $descripcion]
            );
        }

        // 2. Asociar Permisos a los Roles en la tabla intermedia 'rolpermiso'
        $admin = Rol::where('nombre', 'Administrador')->first();
        if ($admin) {
            $todosLosPermisos = Permiso::all();
            foreach ($todosLosPermisos as $permiso) {
                DB::table('rolpermiso')->updateOrInsert([
                    'idPermiso' => $permiso->idPermiso,
                    'idRol' => $admin->idRol
                ]);
            }
        }
    }
}