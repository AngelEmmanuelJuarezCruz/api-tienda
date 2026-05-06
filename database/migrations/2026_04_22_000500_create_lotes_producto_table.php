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
        Schema::create('lotes_producto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('numero_lote', 80);
            $table->date('fecha_caducidad')->nullable();
            $table->unsignedInteger('cantidad_inicial');
            $table->unsignedInteger('cantidad_actual');
            $table->timestamps();

            $table->unique(['producto_id', 'numero_lote']);
            $table->index(['fecha_caducidad', 'cantidad_actual']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lotes_producto');
    }
};
