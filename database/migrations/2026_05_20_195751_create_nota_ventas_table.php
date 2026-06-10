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
        Schema::create('notaVenta', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->integer('nroNotaVenta')->autoIncrement();
            $table->integer('idCliente')->nullable(false);
            $table->integer('idPago')->nullable(false);
            $table->integer('idUsuario')->nullable(false); // Asesor que realiza la venta
            $table->date('fecha')->nullable(false);
            $table->decimal('total', 10, 2)->default(0.00)->nullable(false);

            $table->foreign('idCliente')
                    ->references('idCliente')
                    ->on('cliente')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreign('idPago')
                    ->references('idPago')
                    ->on('pago')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreign('idUsuario')
                    ->references('idUsuario')
                    ->on('usuario')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notaVenta');
    }
};
