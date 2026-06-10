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
        Schema::create('producto', function (Blueprint $table) {
            // Configuraciones de caracteres exigidas
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            // idProducto NO es autoincremental, recibe el mismo ID de productoServicio (Herencia)
            $table->integer('idProducto')->nullable(false); 
            $table->integer('stock')->default(0)->nullable(false);
            $table->string('marca', 100)->nullable();
            $table->string('modelo', 100)->nullable();
            $table->string('numeroSerie', 100)->nullable();

            // Definición explícita de la Clave Primaria (NOT NULL implícito)
            $table->primary('idProducto');

            // Restricción de Integridad Referencial (Herencia) con cascada total
            $table->foreign('idProducto')
                    ->references('idProductoServicio')
                    ->on('productoServicio')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            // Timestamps para auditar cuándo se modificó el stock o datos del hardware
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto');
    }
};
