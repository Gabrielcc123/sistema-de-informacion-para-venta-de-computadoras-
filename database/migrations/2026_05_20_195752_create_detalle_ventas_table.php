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
        Schema::create('detalleVenta', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->integer('idDetalleVenta')->autoIncrement();
            $table->integer('nroNotaVenta')->nullable(false);
            $table->integer('idProductoServicio')->nullable(false);
            $table->integer('cantidad')->nullable(false);
            $table->decimal('precioUnitario', 10, 2)->nullable(false);
            $table->decimal('subTotal', 10, 2)->nullable(false);

            $table->primary('idDetalleVenta');

            $table->foreign('nroNotaVenta')
                    ->references('nroNotaVenta')
                    ->on('notaVenta')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreign('idProductoServicio')
                    ->references('idProductoServicio')
                    ->on('productoServicio')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalleVenta');
    }
};
