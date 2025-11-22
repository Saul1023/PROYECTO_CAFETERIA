<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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
            $table->date('fecha_reservacion');
            $table->time('hora_reservacion');
            $table->integer('numero_personas');
            $table->enum('estado', ['pendiente', 'confirmada', 'completada', 'cancelada', 'no_asistio'])
                ->default('pendiente');
            $table->text('observaciones')->nullable();

            // Nuevos campos para el sistema de pago
            $table->string('comprobante_pago')->nullable(); // Ruta de la imagen del comprobante
            $table->decimal('monto_pago', 10, 2)->default(30.00); // Monto de la reserva
            $table->timestamp('fecha_pago')->nullable(); // Cuando se subió el comprobante
            $table->timestamp('fecha_confirmacion')->nullable(); // Cuando el admin confirmó
            $table->string('codigo_qr')->nullable(); // Código QR único para la reserva

            $table->timestampTz('fecha_creacion')->useCurrent();
            $table->timestampTz('fecha_actualizacion')->nullable();

            // Índices para mejorar el rendimiento
            $table->index(['fecha_reservacion', 'hora_reservacion']);
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservaciones');
    }
};
