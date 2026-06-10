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
        Schema::create('productoServicio', function (Blueprint $table) {
            // Configuraciones de caracteres exigidas
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            // Estructura de columnas
            $table->integer('idProductoServicio')->autoIncrement(); // PK explícita NOT NULL
            $table->integer('idCategoria')->nullable(false); // Campo para la FK
            $table->string('nombre', 150)->nullable(false);
            $table->decimal('precioUnitario', 10, 2)->nullable(false);
            $table->string('garantia', 100)->nullable();
            $table->string('tipo', 50)->nullable(false); // Almacena si es 'Producto' o 'Servicio'

            // Restricción de Llave Foránea con Cascada total
            $table->foreign('idCategoria')
                    ->references('idCategoria')
                    ->on('categoria')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            // Auditoría para control de precios e ítems modificados
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productoServicio');
    }
};
