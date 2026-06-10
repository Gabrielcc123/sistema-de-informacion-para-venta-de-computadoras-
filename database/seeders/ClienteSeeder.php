<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        Cliente::firstOrCreate(
            ['ci' => '13538534'],
            [
                'nombre' => 'Gabno',
                'apellido' => 'condori',
                'telefono' => '65847384',
            ]
        );

        Cliente::firstOrCreate(
            ['ci' => '00000000'],
            [
                'nombre' => 'Tobias',
                'apellido' => 'Prueba',
                'telefono' => 'S/N',
            ]
        );
    }
}