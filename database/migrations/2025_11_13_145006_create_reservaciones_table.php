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
        Schema::create('reservaciones', function (Blueprint $table) {
            $table->id('id_reservacion');
            $table->foreignId('id_mesa')
                ->constrained('mesas', 'id_mesa')
                ->onDelete('restrict');
            $table->foreignId('id_usuario')
                ->nullable()
                ->constrained('usuarios', 'id_usuario')
                ->onDelete('set null');
            // CAMBIOS AQUÍ:
            $table->date('fecha_reservacion')->nullable(); // Cambiado de timestamp a date
            $table->time('hora_reservacion')->nullable(); // Cambiado de timestamp a time
            $table->integer('numero_personas');
            $table->enum('estado', ['pendiente', 'confirmada', 'completada', 'cancelada', 'no_asistio'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->timestampTz('fecha_creacion')->useCurrent();
            $table->timestampTz('fecha_actualizacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservaciones');
    }
};