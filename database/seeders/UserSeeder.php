<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Rol;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Buscamos los roles previamente creados
        $adminRole = Rol::where('nombre', 'Administrador')->first();
        $vendedorRole = Rol::where('nombre', 'Vendedor')->first();

        // Si los roles existen, creamos los usuarios
        if ($adminRole) {
            User::firstOrCreate(
                ['email' => 'admin@iris.com'],
                [
                    'name' => 'Admin Principal',
                    'password' => Hash::make('password'),
                    'role_id' => $adminRole->id,
                ]
            );
        }

        if ($vendedorRole) {
            User::firstOrCreate(
                ['email' => 'vendedor@iris.com'],
                [
                    'name' => 'Vendedor',
                    'password' => Hash::make('password'),
                    'role_id' => $vendedorRole->id,
                ]
            );
        }
    }
}
//no usamos esta tabla , pero esta alli como relleno del sistema de laravel 