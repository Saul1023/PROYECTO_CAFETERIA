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
        Schema::create('promocion_producto', function (Blueprint $table) {
            $table->id('id_promocion_producto');
            $table->foreignId('id_promocion')
                ->constrained('promociones', 'id_promocion')
                ->onDelete('cascade');
            $table->foreignId('id_producto')
                ->constrained('productos', 'id_producto')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promocion_producto');
    }
};
