<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('alertas_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('tipo', 20);
            $table->boolean('atendida')->default(false);
            $table->string('mensaje', 200);
            $table->timestamp('fecha_alerta')->useCurrent();
            $table->timestamps();

            $table->index(['producto_id', 'atendida']);
            $table->unique(['producto_id', 'tipo', 'atendida']);
        });

        Schema::create('bitacora_movimientos_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->restrictOnDelete()->cascadeOnUpdate();
            $table->string('origen', 20);
            $table->unsignedInteger('cantidad');
            $table->unsignedInteger('stock_antes');
            $table->unsignedInteger('stock_despues');
            $table->string('referencia', 120)->nullable();
            $table->timestamp('fecha_movimiento')->useCurrent();
            $table->timestamps();

            $table->index(['producto_id', 'fecha_movimiento']);
            $table->index(['origen', 'fecha_movimiento']);
        });

        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::unprepared('DROP TRIGGER IF EXISTS tr_entradas_ai_actualiza_stock');
        DB::unprepared('DROP TRIGGER IF EXISTS tr_salidas_bi_valida_stock');
        DB::unprepared('DROP TRIGGER IF EXISTS tr_salidas_ai_actualiza_stock');
        DB::unprepared('DROP TRIGGER IF EXISTS tr_detalle_venta_bi_calcula_subtotal');
        DB::unprepared('DROP TRIGGER IF EXISTS tr_detalle_venta_bi_valida_stock');
        DB::unprepared('DROP TRIGGER IF EXISTS tr_detalle_venta_ai_descuenta_stock');
        DB::unprepared('DROP TRIGGER IF EXISTS tr_productos_au_genera_alertas_stock');

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_registrar_entrada');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_registrar_salida');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_registrar_venta_simple');

        DB::unprepared('DROP EVENT IF EXISTS ev_cierre_alertas_stock_resueltas');

        DB::unprepared(
            'CREATE TRIGGER tr_entradas_ai_actualiza_stock
            AFTER INSERT ON entradas_inventario
            FOR EACH ROW
            BEGIN
                DECLARE v_stock_antes INT;

                SELECT stock_actual INTO v_stock_antes
                FROM productos
                WHERE id = NEW.producto_id;

                UPDATE productos
                SET stock_actual = stock_actual + NEW.cantidad,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = NEW.producto_id;

                INSERT INTO bitacora_movimientos_stock
                    (producto_id, origen, cantidad, stock_antes, stock_despues, referencia, created_at, updated_at)
                VALUES
                    (NEW.producto_id, "ENTRADA", NEW.cantidad, v_stock_antes, v_stock_antes + NEW.cantidad, CONCAT("entrada:", NEW.id), NOW(), NOW());
            END'
        );

        DB::unprepared(
            'CREATE TRIGGER tr_salidas_bi_valida_stock
            BEFORE INSERT ON salidas_inventario
            FOR EACH ROW
            BEGIN
                DECLARE v_stock_actual INT;

                SELECT stock_actual INTO v_stock_actual
                FROM productos
                WHERE id = NEW.producto_id;

                IF v_stock_actual < NEW.cantidad THEN
                    SIGNAL SQLSTATE "45000"
                    SET MESSAGE_TEXT = "Stock insuficiente para registrar la salida de inventario";
                END IF;
            END'
        );

        DB::unprepared(
            'CREATE TRIGGER tr_salidas_ai_actualiza_stock
            AFTER INSERT ON salidas_inventario
            FOR EACH ROW
            BEGIN
                DECLARE v_stock_antes INT;

                SELECT stock_actual INTO v_stock_antes
                FROM productos
                WHERE id = NEW.producto_id;

                UPDATE productos
                SET stock_actual = stock_actual - NEW.cantidad,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = NEW.producto_id;

                INSERT INTO bitacora_movimientos_stock
                    (producto_id, origen, cantidad, stock_antes, stock_despues, referencia, created_at, updated_at)
                VALUES
                    (NEW.producto_id, "SALIDA", NEW.cantidad, v_stock_antes, v_stock_antes - NEW.cantidad, CONCAT("salida:", NEW.id), NOW(), NOW());
            END'
        );

        DB::unprepared(
            'CREATE TRIGGER tr_detalle_venta_bi_calcula_subtotal
            BEFORE INSERT ON detalles_venta
            FOR EACH ROW
            BEGIN
                SET NEW.subtotal = NEW.cantidad * NEW.precio_unitario;
            END'
        );

        DB::unprepared(
            'CREATE TRIGGER tr_detalle_venta_bi_valida_stock
            BEFORE INSERT ON detalles_venta
            FOR EACH ROW
            BEGIN
                DECLARE v_stock_actual INT;

                SELECT stock_actual INTO v_stock_actual
                FROM productos
                WHERE id = NEW.producto_id;

                IF v_stock_actual < NEW.cantidad THEN
                    SIGNAL SQLSTATE "45000"
                    SET MESSAGE_TEXT = "Stock insuficiente para registrar la venta";
                END IF;
            END'
        );

        DB::unprepared(
            'CREATE TRIGGER tr_detalle_venta_ai_descuenta_stock
            AFTER INSERT ON detalles_venta
            FOR EACH ROW
            BEGIN
                DECLARE v_stock_antes INT;

                SELECT stock_actual INTO v_stock_antes
                FROM productos
                WHERE id = NEW.producto_id;

                UPDATE productos
                SET stock_actual = stock_actual - NEW.cantidad,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = NEW.producto_id;

                UPDATE ventas
                SET total = (
                    SELECT COALESCE(SUM(subtotal), 0)
                    FROM detalles_venta
                    WHERE venta_id = NEW.venta_id
                ),
                updated_at = CURRENT_TIMESTAMP
                WHERE id = NEW.venta_id;

                INSERT INTO bitacora_movimientos_stock
                    (producto_id, origen, cantidad, stock_antes, stock_despues, referencia, created_at, updated_at)
                VALUES
                    (NEW.producto_id, "VENTA", NEW.cantidad, v_stock_antes, v_stock_antes - NEW.cantidad, CONCAT("venta_detalle:", NEW.id), NOW(), NOW());
            END'
        );

        DB::unprepared(
            'CREATE TRIGGER tr_productos_au_genera_alertas_stock
            AFTER UPDATE ON productos
            FOR EACH ROW
            BEGIN
                IF NEW.stock_actual = 0 THEN
                    INSERT IGNORE INTO alertas_stock (producto_id, tipo, atendida, mensaje, fecha_alerta, created_at, updated_at)
                    VALUES (NEW.id, "SIN_STOCK", 0, CONCAT("El producto ", NEW.nombre, " se quedo sin inventario"), NOW(), NOW(), NOW());
                ELSE
                    DELETE FROM alertas_stock
                    WHERE producto_id = NEW.id
                      AND tipo = "SIN_STOCK"
                      AND atendida = 0;
                END IF;

                IF NEW.stock_actual <= NEW.stock_minimo THEN
                    INSERT IGNORE INTO alertas_stock (producto_id, tipo, atendida, mensaje, fecha_alerta, created_at, updated_at)
                    VALUES (NEW.id, "STOCK_BAJO", 0, CONCAT("El producto ", NEW.nombre, " alcanzo stock minimo"), NOW(), NOW(), NOW());
                ELSE
                    DELETE FROM alertas_stock
                    WHERE producto_id = NEW.id
                      AND tipo = "STOCK_BAJO"
                      AND atendida = 0;
                END IF;
            END'
        );

        DB::unprepared(
            'CREATE PROCEDURE sp_registrar_entrada(
                IN p_producto_id BIGINT UNSIGNED,
                IN p_usuario_id BIGINT UNSIGNED,
                IN p_proveedor_id BIGINT UNSIGNED,
                IN p_cantidad INT UNSIGNED,
                IN p_costo_unitario DECIMAL(10,2),
                IN p_notas TEXT
            )
            BEGIN
                INSERT INTO entradas_inventario
                    (producto_id, usuario_id, proveedor_id, cantidad, costo_unitario, fecha, notas, created_at, updated_at)
                VALUES
                    (p_producto_id, p_usuario_id, p_proveedor_id, p_cantidad, p_costo_unitario, NOW(), p_notas, NOW(), NOW());
            END'
        );

        DB::unprepared(
            'CREATE PROCEDURE sp_registrar_salida(
                IN p_producto_id BIGINT UNSIGNED,
                IN p_usuario_id BIGINT UNSIGNED,
                IN p_cantidad INT UNSIGNED,
                IN p_motivo VARCHAR(10),
                IN p_justificacion TEXT
            )
            BEGIN
                IF p_motivo NOT IN ("MERMA", "AJUSTE") THEN
                    SIGNAL SQLSTATE "45000"
                    SET MESSAGE_TEXT = "Motivo invalido. Use MERMA o AJUSTE";
                END IF;

                INSERT INTO salidas_inventario
                    (producto_id, usuario_id, cantidad, motivo, fecha, justificacion, created_at, updated_at)
                VALUES
                    (p_producto_id, p_usuario_id, p_cantidad, p_motivo, NOW(), p_justificacion, NOW(), NOW());
            END'
        );

        DB::unprepared(
            'CREATE PROCEDURE sp_registrar_venta_simple(
                IN p_usuario_id BIGINT UNSIGNED,
                IN p_folio VARCHAR(40),
                IN p_producto_id BIGINT UNSIGNED,
                IN p_cantidad INT UNSIGNED,
                IN p_precio_unitario DECIMAL(10,2)
            )
            BEGIN
                DECLARE v_venta_id BIGINT UNSIGNED;

                START TRANSACTION;

                INSERT INTO ventas (usuario_id, folio, total, fecha, created_at, updated_at)
                VALUES (p_usuario_id, p_folio, 0, NOW(), NOW(), NOW());

                SET v_venta_id = LAST_INSERT_ID();

                INSERT INTO detalles_venta (venta_id, producto_id, cantidad, precio_unitario, subtotal, created_at, updated_at)
                VALUES (v_venta_id, p_producto_id, p_cantidad, p_precio_unitario, p_cantidad * p_precio_unitario, NOW(), NOW());

                COMMIT;

                SELECT v_venta_id AS venta_id;
            END'
        );

        DB::unprepared(
            'CREATE EVENT ev_cierre_alertas_stock_resueltas
            ON SCHEDULE EVERY 1 DAY
            STARTS CURRENT_TIMESTAMP + INTERVAL 1 DAY
            DO
              UPDATE alertas_stock a
              INNER JOIN productos p ON p.id = a.producto_id
              SET a.atendida = 1,
                  a.updated_at = NOW()
              WHERE a.atendida = 0
                AND (
                    (a.tipo = "SIN_STOCK" AND p.stock_actual > 0)
                    OR (a.tipo = "STOCK_BAJO" AND p.stock_actual > p.stock_minimo)
                )'
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::unprepared('DROP EVENT IF EXISTS ev_cierre_alertas_stock_resueltas');

            DB::unprepared('DROP PROCEDURE IF EXISTS sp_registrar_venta_simple');
            DB::unprepared('DROP PROCEDURE IF EXISTS sp_registrar_salida');
            DB::unprepared('DROP PROCEDURE IF EXISTS sp_registrar_entrada');

            DB::unprepared('DROP TRIGGER IF EXISTS tr_productos_au_genera_alertas_stock');
            DB::unprepared('DROP TRIGGER IF EXISTS tr_detalle_venta_ai_descuenta_stock');
            DB::unprepared('DROP TRIGGER IF EXISTS tr_detalle_venta_bi_valida_stock');
            DB::unprepared('DROP TRIGGER IF EXISTS tr_detalle_venta_bi_calcula_subtotal');
            DB::unprepared('DROP TRIGGER IF EXISTS tr_salidas_ai_actualiza_stock');
            DB::unprepared('DROP TRIGGER IF EXISTS tr_salidas_bi_valida_stock');
            DB::unprepared('DROP TRIGGER IF EXISTS tr_entradas_ai_actualiza_stock');
        }

        Schema::dropIfExists('bitacora_movimientos_stock');
        Schema::dropIfExists('alertas_stock');
    }
};
