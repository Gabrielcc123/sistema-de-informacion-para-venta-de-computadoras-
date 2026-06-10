<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        Usuario::firstOrCreate(
            ['email' => 'admin@iris.com'],
            [
                'nombre' => 'Admin',
                'apellido' => 'Principal',
                'password' => Hash::make('password'),
                'telefono' => '70000001',
                'estado' => 1,
                'tipoSupervisor' => 1,
                'tipoAssesor' => 0,
                'tipoTecnico' => 0,
            ]
        );

        Usuario::firstOrCreate(
            ['email' => 'vendedor@iris.com'],
            [
                'nombre' => 'Vendedor',
                'apellido' => 'Sistema',
                'password' => Hash::make('password'),
                'telefono' => '70000002',
                'estado' => 1,
                'tipoSupervisor' => 0,
                'tipoAssesor' => 1,
                'tipoTecnico' => 0,
            ]
        );

        Usuario::firstOrCreate(
            ['email' => 'tecnico@iris.com'],
            [
                'nombre' => 'Tecnico',
                'apellido' => 'Sistema',
                'password' => Hash::make('password'),
                'telefono' => '70000003',
                'estado' => 1,
                'tipoSupervisor' => 0,
                'tipoAssesor' => 0,
                'tipoTecnico' => 1,
            ]
        );
    }
}