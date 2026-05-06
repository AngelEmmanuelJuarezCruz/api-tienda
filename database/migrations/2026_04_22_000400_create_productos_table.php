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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained('categorias')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('proveedor_id')->constrained('proveedores')->restrictOnDelete()->cascadeOnUpdate();
            $table->string('nombre', 160);
            $table->string('sku', 80)->unique();
            $table->text('descripcion')->nullable();
            $table->decimal('precio_compra', 10, 2)->default(0);
            $table->decimal('precio_venta', 10, 2);
            $table->unsignedInteger('stock_actual')->default(0);
            $table->unsignedInteger('stock_minimo')->default(0);
            $table->boolean('tiene_caducidad')->default(false);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['categoria_id', 'activo']);
            $table->index(['stock_actual', 'stock_minimo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
