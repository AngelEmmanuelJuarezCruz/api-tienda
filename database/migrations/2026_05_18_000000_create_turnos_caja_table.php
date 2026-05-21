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
        Schema::create('turnos_caja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('restrict');
            $table->string('turno', 100);
            $table->decimal('fondo_inicial', 10, 2)->default(0);
            $table->decimal('ingresos_ventas', 10, 2)->default(0);
            $table->decimal('gastos', 10, 2)->default(0);
            $table->decimal('efectivo_esperado', 10, 2)->default(0);
            $table->decimal('efectivo_real', 10, 2)->nullable();
            $table->decimal('diferencia', 10, 2)->nullable();
            $table->enum('estado', ['Abierto', 'Cerrado'])->default('Abierto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turnos_caja');
    }
};
