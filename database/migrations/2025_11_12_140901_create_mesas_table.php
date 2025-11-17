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
        Schema::create('mesas', function (Blueprint $table) {
            $table->id('id_mesa');
            $table->string('numero_mesa', 10)->unique();;
            $table->integer('capacidad');
            $table->enum('estado', ['disponible', 'ocupada', 'reservada'])->default('disponible');
            $table->boolean('activa')->default(true); // Para desactivar sin eliminar
            $table->string('ubicacion', 50)->nullable();// Terraza, Interior, VIP
            $table->timestamp('fecha_creacion')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesas');
    }
};
