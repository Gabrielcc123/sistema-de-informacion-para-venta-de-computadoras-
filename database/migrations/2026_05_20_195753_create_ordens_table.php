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
        Schema::create('orden', function (Blueprint $table) {
            // Configuraciones de caracteres exigidas
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            // Estructura de columnas
            $table->integer('idOrden')->autoIncrement(); // PK explícita NOT NULL
            $table->integer('idEquipo')->nullable(false);
            $table->integer('nroNotaVenta')->nullable(false);
            $table->integer('idTecnico')->nullable(false); // FK hacia idUsuario del técnico
            
            $table->text('descripcion')->nullable();
            $table->string('estado', 50)->default('Pendiente'); // Ej: 'Pendiente', 'En diagnóstico', 'En reparación', 'Finalizado', 'Entregado'

            // Restricciones de Integridad Referencial con Cascada Total
            $table->foreign('idEquipo')
                    ->references('idEquipo')
                    ->on('equipo')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreign('nroNotaVenta')
                    ->references('nroNotaVenta')
                    ->on('notaVenta')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreign('idTecnico')
                    ->references('idUsuario')
                    ->on('usuario')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            // Timestamps activos para controlar los tiempos de reparación
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden');
    }
};
