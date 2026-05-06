@⁨Angel⁩ INT-02 — ARQUITECTO DE BASE DE DATOS

Objetivo 1
Diseñar el Diagrama ERD completo del sistema
Qué debe entregar exactamente:

Diagrama con las 9 tablas del negocio (listadas arriba en la sección de modelos)
Relaciones correctas dibujadas: qué tabla tiene llaves foráneas de cuál
Indicar qué campos son obligatorios, cuáles opcionales y los tipos de dato
Herramienta sugerida: dbdiagram.io (gratuita)

El jueves el profesor ve: Link al diagrama o imagen exportada con todas las relaciones visibles y justificadas

Objetivo 2
Crear y ejecutar migraciones de las tablas principales
Qué debe entregar exactamente:

Migración usuarios: id, nombre, email, password, rol ENUM('dueño','encargado','cajero'), activo (boolean), timestamps
Migración categorias: id, nombre, timestamps
Migración proveedores: id, nombre, telefono, contacto, timestamps
Migración productos: todos los campos del modelo definido arriba, con foreign key a categorias y proveedores
php artisan migrate corre sin errores

El jueves el profesor ve: Screenshot de las 4 tablas creadas en HeidiSQL + código de las migraciones

Objetivo 3
Crear migraciones de movimientos e inicializar Seeders
Qué debe entregar exactamente:

Migración lotes_producto: producto_id, numero_lote, fecha_caducidad, cantidad_inicial, cantidad_actual
Migración entradas_inventario: producto_id, usuario_id, proveedor_id, cantidad, costo_unitario, fecha, notas
Migración salidas_inventario: producto_id, usuario_id, cantidad, motivo ENUM('MERMA','AJUSTE'), fecha, justificacion
Migración ventas y detalles_venta
UserSeeder con 3 usuarios reales (uno por rol, contraseña: password123 encriptada)
CategoriaSeeder con 5 categorías reales: Abarrotes, Lácteos, Bebidas, Limpieza, Dulcería
ProductoSeeder con 8 productos típicos de tienda (arroz, leche, jabón, refresco, etc.), 2 de ellos con stock_actual < stock_minimo y 3 con tiene_caducidad = true

El jueves el profesor ve: Screenshot de las tablas con datos reales + php artisan db:seed corriendo sin errores