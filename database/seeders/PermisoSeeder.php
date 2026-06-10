<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permiso;

class PermisoSeeder extends Seeder
{
    public function run(): void
    {
        $permisos = [
            ['nombre' => 'ver_inventario', 'descripcion' => 'Visualizar productos y categorías'],
            ['nombre' => 'modificar_inventario', 'descripcion' => 'Crear, editar y eliminar productos'],
            ['nombre' => 'gestionar_ventas', 'descripcion' => 'Registrar notas de venta y ver detalles'],
            ['nombre' => 'gestionar_servicio', 'descripcion' => 'Administrar órdenes de servicio técnico y equipos'],
            ['nombre' => 'ver_bitacora', 'descripcion' => 'Acceso de solo lectura a la auditoría del sistema'],
        ];

        foreach ($permisos as $permiso) {
            Permiso::firstOrCreate(
                ['nombre' => $permiso['nombre']],
                ['descripcion' => $permiso['descripcion']]
            );
        }
    }
}