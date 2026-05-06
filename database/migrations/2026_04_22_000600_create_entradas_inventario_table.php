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
        Schema::create('entradas_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('usuario_id')->constrained('users')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('proveedor_id')->constrained('proveedores')->restrictOnDelete()->cascadeOnUpdate();
            $table->unsignedInteger('cantidad');
            $table->decimal('costo_unitario', 10, 2);
            $table->dateTime('fecha');
            $table->text('notas')->nullable();
            $table->timestamps();

            $table->index(['producto_id', 'fecha']);
            $table->index(['usuario_id', 'fecha']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entradas_inventario');
    }
};
