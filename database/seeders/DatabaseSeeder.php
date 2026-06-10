<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermisoSeeder::class,   // 1. Permisos bases
            RolSeeder::class,       // 2. Roles
            UsuarioSeeder::class,   // 3. Usuarios
            CategoriaSeeder::class, // 4. Catálogo de categorías
            PagoSeeder::class,      // 5. Métodos de pago
        ]);
    }
}