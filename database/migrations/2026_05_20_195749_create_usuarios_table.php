<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->integer('idUsuario')->autoIncrement();
            $table->string('nombre', 100)->nullable(false);
            $table->string('apellido', 100)->nullable(false);
            
            // AGREGADO: Campo email único
            $table->string('email', 150)->unique()->nullable(false); 
            
            $table->string('password', 255)->nullable(false);
            $table->string('telefono', 30)->nullable();
            $table->boolean('estado')->default(true)->nullable(false);

            $table->boolean('tipoAssesor')->default(false)->nullable(false);
            $table->boolean('tipoSupervisor')->default(false)->nullable(false);
            $table->boolean('tipoTecnico')->default(false)->nullable(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};