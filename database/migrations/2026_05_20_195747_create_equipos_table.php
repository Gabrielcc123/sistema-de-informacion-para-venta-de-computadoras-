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
        Schema::create('equipo', function (Blueprint $table) {
            // Configuraciones de caracteres exigidas
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            // Estructura de columnas
            $table->integer('idEquipo')->autoIncrement(); // PK explícita NOT NULL
            $table->string('marca', 100)->nullable();
            $table->string('modelo', 100)->nullable();
            $table->string('numeroSerie', 100)->nullable();
            $table->string('estado', 50)->default('Recibido'); // Ej: 'Recibido', 'En Diagnóstico', 'En Reparación', 'Listo', 'Entregado'
            $table->text('descripcion')->nullable();
            $table->string('gama', 50)->nullable(); // Ej: 'Alta', 'Media', 'Baja'

            // Auditoría para el rastreo del flujo de mantenimiento
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipo');
    }
};
