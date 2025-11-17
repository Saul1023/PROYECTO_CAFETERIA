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
        Schema::create('detalle_pedido', function (Blueprint $table) {
            $table->id('id_detalle_pedido');
            $table->foreignId('id_pedido')
                ->constrained('pedidos', 'id_pedido')
                ->onDelete('cascade');
            $table->foreignId('id_producto')
                ->constrained('productos', 'id_producto')
                ->onDelete('restrict');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2);
            $table->enum('estado_preparacion', ['pendiente', 'en_preparacion', 'listo', 'entregado'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->timestampTz('fecha_creacion')->useCurrent();
            $table->timestampTz('fecha_actualizacion')->nullable();

            $table->index('id_pedido');
            $table->index('id_producto');
            $table->index('estado_preparacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_pedido');
    }
};
