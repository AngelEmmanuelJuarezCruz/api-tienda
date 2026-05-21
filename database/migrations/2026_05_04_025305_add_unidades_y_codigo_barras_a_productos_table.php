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
        Schema::table('productos', function (Blueprint $table) {
            $table->string('codigo_barras', 100)->nullable()->unique()->after('nombre');
            $table->enum('unidad_medida', ['pieza', 'caja', 'kilo', 'metro'])->default('pieza')->after('codigo_barras');
            $table->date('fecha_caducidad')->nullable()->after('unidad_medida');
            $table->string('sku', 80)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('productos', 'codigo_barras')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->dropUnique('productos_codigo_barras_unique');
                $table->dropColumn(['codigo_barras', 'unidad_medida', 'fecha_caducidad']);
            });
        } elseif (Schema::hasColumn('productos', 'unidad_medida')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->dropColumn(['unidad_medida', 'fecha_caducidad']);
            });
        }

        Schema::table('productos', function (Blueprint $table) {
            $table->string('sku', 80)->nullable(false)->change();
        });
    }
};
