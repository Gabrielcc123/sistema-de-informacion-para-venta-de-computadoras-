<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pago;

class PagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $metodos = [
            ['tipoPago' => 'Efectivo',      'descripcion' => 'Pago en billetes y monedas nacionales'],
            ['tipoPago' => 'QR',             'descripcion' => 'Escaneo de código QR para transferencia inmediata'],
            ['tipoPago' => 'Transferencia',  'descripcion' => 'Depósito o transferencia bancaria'],
            ['tipoPago' => 'Tarjeta',        'descripcion' => 'Pago con tarjeta de débito o crédito'],
        ];

        foreach ($metodos as $metodo) {
            Pago::firstOrCreate(
                ['tipoPago' => $metodo['tipoPago']],
                $metodo
            );
        }
    }
}
