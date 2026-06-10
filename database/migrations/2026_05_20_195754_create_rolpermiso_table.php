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
        Schema::create('rolpermiso', function (Blueprint $table) {
            // Configuraciones de caracteres exigidas
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            // Estructura de columnas (Claves Foráneas que actuarán como PK compuesta)
            $table->integer('idPermiso')->nullable(false);
            $table->integer('idRol')->nullable(false);

            // Definición explícita de la Clave Primaria Compuesta (Garantiza NOT NULL)
            $table->primary(['idPermiso', 'idRol']);

            // Restricciones de Integridad Referencial con Cascada Total
            $table->foreign('idPermiso')
                    ->references('idPermiso')
                    ->on('permiso')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreign('idRol')
                    ->references('idRol')
                    ->on('rol')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            // No lleva timestamps por tratarse de una tabla puente indexada
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rolpermiso');
    }
};
