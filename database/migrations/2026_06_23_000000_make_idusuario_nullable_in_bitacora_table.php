<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Permite que la bitácora registre acciones de usuarios no autenticados
     * (intentos de login fallidos, bloqueos por rate-limit, accesos a cuentas
     * suspendidas, etc.). En SQL estándar, un valor NULL en una columna con
     * FK NO se valida contra la tabla referenciada, por lo que se conserva la
     * integridad referencial para las filas que sí tengan un usuario asociado.
     */
    public function up(): void
    {
        Schema::table('bitacora', function (Blueprint $table) {
            // 1. Eliminar la FK existente para poder modificar la columna
            $table->dropForeign(['idUsuario']);

            // 2. Hacer nullable la columna idUsuario
            $table->integer('idUsuario')->nullable()->change();

            // 3. Re-crear la FK con las mismas reglas originales (cascade)
            $table->foreign('idUsuario')
                    ->references('idUsuario')
                    ->on('usuario')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bitacora', function (Blueprint $table) {
            // Para volver a NOT NULL, primero se eliminan filas huérfanas con NULL
            \DB::table('bitacora')->whereNull('idUsuario')->delete();

            $table->dropForeign(['idUsuario']);
            $table->integer('idUsuario')->nullable(false)->change();
            $table->foreign('idUsuario')
                    ->references('idUsuario')
                    ->on('usuario')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
        });
    }
};
