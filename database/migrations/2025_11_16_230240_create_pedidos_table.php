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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id('id_pedido');
            $table->string('numero_pedido', 20)->unique();
            $table->foreignId('id_usuario')
                ->constrained('usuarios', 'id_usuario')
                ->onDelete('restrict');
            $table->foreignId('id_mesa')
                ->nullable()
                ->constrained('mesas', 'id_mesa')
                ->onDelete('restrict');
            $table->foreignId('id_reservacion')
                ->nullable()
                ->constrained('reservaciones', 'id_reservacion')
                ->onDelete('restrict');
            $table->enum('estado', ['pendiente', 'en_preparacion', 'listo', 'entregado', 'cancelado'])->default('pendiente');
            $table->enum('tipo_consumo', ['mesa', 'para_llevar'])->default('mesa');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->text('observaciones')->nullable();
            $table->timestampTz('fecha_pedido')->useCurrent();
            $table->timestampTz('fecha_entrega')->nullable();
            $table->timestampTz('fecha_actualizacion')->nullable();

            $table->index('id_usuario');
            $table->index('id_mesa');
            $table->index('id_reservacion');
            $table->index('estado');
            $table->index('fecha_pedido');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
