USE api_tienda;

-- DEMO BASE DE DATOS ACTIVA (3-5 MIN)
-- Precondicion: migraciones y seeders ejecutados.

-- 0) Contexto inicial del producto demo.
SELECT id, nombre, sku, stock_actual, stock_minimo
FROM productos
WHERE sku = 'ABR-ARROZ-1KG';

-- 1) Registrar ENTRADA por procedimiento almacenado.
CALL sp_registrar_entrada(
  1,          -- producto_id
  1,          -- usuario_id
  1,          -- proveedor_id
  12,         -- cantidad
  19.50,      -- costo_unitario
  'Entrada demo producto integrador'
);

-- Evidencia: stock aumenta por trigger.
SELECT id, nombre, sku, stock_actual, stock_minimo
FROM productos
WHERE id = 1;

-- 2) Registrar SALIDA por procedimiento almacenado.
CALL sp_registrar_salida(
  1,                  -- producto_id
  1,                  -- usuario_id
  3,                  -- cantidad
  'MERMA',            -- motivo
  'Merma controlada demo'
);

-- Evidencia: stock disminuye por trigger y queda trazabilidad.
SELECT id, nombre, sku, stock_actual, stock_minimo
FROM productos
WHERE id = 1;

-- 3) Registrar VENTA simple (genera venta + detalle + descuento de stock + recalculo total).
SET @folio_demo = CONCAT('DEMO-', DATE_FORMAT(NOW(), '%Y%m%d%H%i%s'));
CALL sp_registrar_venta_simple(
  1,          -- usuario_id
  @folio_demo,
  1,          -- producto_id
  2,          -- cantidad
  24.00       -- precio_unitario
);

-- Evidencia: total de venta y detalle.
SELECT id, folio, total, fecha
FROM ventas
WHERE folio = @folio_demo;

SELECT dv.id, dv.venta_id, dv.producto_id, dv.cantidad, dv.precio_unitario, dv.subtotal
FROM detalles_venta dv
JOIN ventas v ON v.id = dv.venta_id
WHERE v.folio = @folio_demo;

-- 4) Evidencia de bitacora automatica.
SELECT id, producto_id, origen, cantidad, stock_antes, stock_despues, referencia, fecha_movimiento
FROM bitacora_movimientos_stock
WHERE producto_id = 1
ORDER BY id DESC
LIMIT 10;

-- 5) Forzar alerta de stock bajo para evidencia.
UPDATE productos
SET stock_actual = stock_minimo
WHERE id = 1;

SELECT id, producto_id, tipo, atendida, mensaje, fecha_alerta
FROM alertas_stock
WHERE producto_id = 1
ORDER BY id DESC;

-- 6) Probar regla de integridad: esta operacion debe fallar por stock insuficiente.
-- Esperado: SQLSTATE[45000] con mensaje de stock insuficiente.
-- CALL sp_registrar_salida(1, 1, 99999, 'AJUSTE', 'Prueba de validacion de integridad');

-- 7) Restaurar estado razonable del producto demo (opcional, descomentarlo si se requiere).
-- UPDATE productos
-- SET stock_actual = 15
-- WHERE id = 1;
