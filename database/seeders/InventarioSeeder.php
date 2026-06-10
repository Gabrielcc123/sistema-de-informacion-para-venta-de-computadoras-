<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;
use App\Models\ProductoServicio;
use App\Models\Producto;

class InventarioSeeder extends Seeder
{
    public function run(): void
    {
        $catServicios = Categoria::where('nombre', 'Servicios Técnicos')->first();
        $catPerifericos = Categoria::where('nombre', 'Periféricos')->first();
        $catEquipos = Categoria::where('nombre', 'Equipos Armados')->first();

        $servicios = [
            [
                'nombre' => 'Mantenimiento',
                'idCategoria' => $catServicios->idCategoria,
                'precioUnitario' => 150.00,
                'garantia' => '1',
                'tipo' => 'Servicio',
            ],
            [
                'nombre' => 'Ensamblaje',
                'idCategoria' => $catServicios->idCategoria,
                'precioUnitario' => 120.00,
                'garantia' => null,
                'tipo' => 'Servicio',
            ],
        ];

        foreach ($servicios as $s) {
            ProductoServicio::firstOrCreate(
                ['nombre' => $s['nombre']],
                $s
            );
        }

        $productos = [
            [
                'ps' => [
                    'nombre' => 'teclado Gamer',
                    'idCategoria' => $catPerifericos->idCategoria,
                    'precioUnitario' => 350.00,
                    'garantia' => '5',
                    'tipo' => 'Producto',
                ],
                'producto' => [
                    'stock' => 122,
                    'marca' => 'Red Dragon',
                    'modelo' => 'Kumara K522',
                    'numeroSerie' => 'SN22134123',
                ],
            ],
            [
                'ps' => [
                    'nombre' => 'victusfx',
                    'idCategoria' => $catEquipos->idCategoria,
                    'precioUnitario' => 7000.00,
                    'garantia' => '12',
                    'tipo' => 'Producto',
                ],
                'producto' => [
                    'stock' => 5,
                    'marca' => 'HP',
                    'modelo' => 'Victus',
                    'numeroSerie' => 'S/N',
                ],
            ],
            [
                'ps' => [
                    'nombre' => 'mouse ajazz 139 vm',
                    'idCategoria' => $catPerifericos->idCategoria,
                    'precioUnitario' => 250.00,
                    'garantia' => '6',
                    'tipo' => 'Producto',
                ],
                'producto' => [
                    'stock' => 15,
                    'marca' => 'Ajazz',
                    'modelo' => '139 VM',
                    'numeroSerie' => 'S/N',
                ],
            ],
        ];

        foreach ($productos as $p) {
            $ps = ProductoServicio::firstOrCreate(
                ['nombre' => $p['ps']['nombre']],
                $p['ps']
            );

            if ($ps->wasRecentlyCreated) {
                Producto::create(array_merge(
                    ['idProducto' => $ps->idProductoServicio],
                    $p['producto']
                ));
            }
        }
    }
}