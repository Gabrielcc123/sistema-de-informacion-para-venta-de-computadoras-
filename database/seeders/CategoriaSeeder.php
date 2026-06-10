<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            [
                'nombre'      => 'Componentes PC',
                'descripcion' => 'Procesadores, Tarjetas Madre, Memorias RAM, Tarjetas Gráficas, etc.',
            ],
            [
                'nombre'      => 'Almacenamiento',
                'descripcion' => 'Discos Duros (HDD), Unidades de Estado Sólido (SSD), M.2, Pendrives.',
            ],
            [
                'nombre'      => 'Periféricos',
                'descripcion' => 'Teclados, Ratones, Monitores, Auriculares.',
            ],
            [
                'nombre'      => 'Equipos Armados',
                'descripcion' => 'Laptops y PCs de escritorio pre-ensambladas.',
            ],
            [
                'nombre'      => 'Servicios Técnicos',
                'descripcion' => 'Mantenimiento, formateo, diagnóstico y reparación de equipos.',
            ],
            [
                'nombre'      => 'Accesorios',
                'descripcion' => 'Cables, adaptadores, mochilas, pastas térmicas.',
            ],
        ];

        foreach ($categorias as $cat) {
            Categoria::firstOrCreate(
                ['nombre' => $cat['nombre']],
                $cat
            );
        }
    }
}
