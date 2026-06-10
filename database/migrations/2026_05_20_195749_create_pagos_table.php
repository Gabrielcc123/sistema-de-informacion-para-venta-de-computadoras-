<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pago', function (Blueprint $table) {
            // Configuraciones de caracteres exigidas
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            // Estructura de columnas (Corregido 'ifPago' a 'idPago')
            $table->integer('idPago')->autoIncrement(); // PK explícita NOT NULL
            $table->string('tipoPago', 100)->nullable(false); // Ej: 'Efectivo', 'Tarjeta'
            $table->text('descripcion')->nullable();

            // Sin timestamps por tratarse de un catálogo estático del sistema
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pago');
    }
};
