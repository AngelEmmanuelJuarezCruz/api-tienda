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
        Schema::create('salidas_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('usuario_id')->constrained('users')->restrictOnDelete()->cascadeOnUpdate();
            $table->unsignedInteger('cantidad');
            $table->string('motivo', 20);
            $table->dateTime('fecha');
            $table->text('justificacion')->nullable();
            $table->timestamps();

            $table->index(['producto_id', 'fecha']);
            $table->index(['motivo', 'fecha']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salidas_inventario');
    }
};
