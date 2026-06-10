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
        Schema::create('bitacora', function (Blueprint $table) {
            // Configuraciones de caracteres exigidas
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            // Estructura de columnas
            $table->integer('idBitacora')->autoIncrement(); // PK explícita NOT NULL
            $table->integer('idUsuario')->nullable(false); // FK hacia usuario
            $table->text('accion')->nullable(false);
            $table->string('ip', 45)->nullable(false); // Soporta IPv4 e IPv6
            $table->date('fecha')->nullable(false);
            $table->time('hora')->nullable(false);

            // Restricción de Integridad Referencial con Cascada total
            $table->foreign('idUsuario')
                    ->references('idUsuario')
                    ->on('usuario')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            // No lleva timestamps por diseño de auditoría estática
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacora');
    }
};
