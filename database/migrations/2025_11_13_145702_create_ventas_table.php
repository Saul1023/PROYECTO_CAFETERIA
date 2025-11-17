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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id('id_venta');
            $table->string('numero_venta', 20)->unique();
            $table->foreignId('id_usuario')
                ->constrained('usuarios', 'id_usuario')
                ->onDelete('restrict');
            $table->foreignId('id_reserva')
                ->constrained('reservaciones', 'id_reservacion');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'transferencia', 'yape', 'plin', 'mixto'])->default('efectivo');
            $table->enum('estado_venta', ['pendiente', 'completada', 'anulada'])->default('completada');
            $table->text('observaciones')->nullable();
            $table->timestampTz('fecha_venta')->useCurrent();
            $table->timestampTz('fecha_actualizacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
