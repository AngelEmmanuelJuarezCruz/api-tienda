# Plan de Ejecución — Angel (Semana 2)

**Rol:** Backend Lead — Base de Datos & Arquitecto de Datos  
**Fecha:** 28 de Abril — 5 de Mayo 2026  
**Status:** ✅ EN EJECUCIÓN

---

## 📋 Objetivos Semana 2 (Detalle Técnico)

### Paso 1: ERD (Diagrama de Entidades-Relaciones)
**Status:** ✅ COMPLETADO
- 9 tablas diseñadas y relacionadas
- Restricciones y dependencias validadas
- Campos con tipos correctos y restricciones de unicidad

**Tablas:**
1. `USUARIOS` (id, name, email, password, rol, activo)
2. `CATEGORIAS` (id, nombre, descripcion)
3. `PROVEEDORES` (id, nombre, contacto, telefono, email)
4. `PRODUCTOS` (id, codigo_barras, nombre, id_categoria, id_proveedor, precio_compra, precio_venta, stock_actual, stock_minimo, fecha_caducidad)
5. `LOTES_PRODUCTO` (id, id_producto, cantidad, fecha_caducidad)
6. `ENTRADAS_INVENTARIO` (id, id_producto, id_proveedor, id_usuario, cantidad, costo_unitario, costo_total, fecha_entrada)
7. `SALIDAS_INVENTARIO` (id, id_producto, id_usuario, cantidad, motivo)
8. `VENTAS` (id, id_usuario, total, impuesto, monto_final, estado)
9. `DETALLES_VENTA` (id, id_venta, id_producto, cantidad, precio_unitario, subtotal)

**Relaciones:**
- USUARIOS 1→N VENTAS, ENTRADAS_INVENTARIO, SALIDAS_INVENTARIO
- CATEGORIAS 1→N PRODUCTOS
- PROVEEDORES 1→N PRODUCTOS, ENTRADAS_INVENTARIO
- PRODUCTOS 1→N LOTES, ENTRADAS, SALIDAS, DETALLES_VENTA
- VENTAS 1→N DETALLES_VENTA

---

### Paso 2: Migraciones en Orden Exacto
**Status:** ✅ COMPLETADO (13 migraciones ejecutadas)

**Orden de ejecución:**
1. ✅ `0001_01_01_000000_create_users_table` — CATEGORIAS sin deps
2. ✅ `2026_04_22_000200_create_categorias_table`
3. ✅ `2026_04_22_000300_create_proveedores_table`
4. ✅ `0001_01_01_000000_create_users_table` (con campos rol, activo)
5. ✅ `2026_04_22_000400_create_productos_table` (deps: categorias, proveedores)
6. ✅ `2026_04_22_000500_create_lotes_producto_table` (deps: productos)
7. ✅ `2026_04_22_000600_create_entradas_inventario_table` (deps: productos, usuarios, proveedores)
8. ✅ `2026_04_22_000700_create_salidas_inventario_table` (deps: productos, usuarios)
9. ✅ `2026_04_22_000800_create_ventas_table` (deps: usuarios)
10. ✅ `2026_04_22_000900_create_detalles_venta_table` (deps: ventas, productos)
11. ✅ `2026_04_25_000100_create_active_database_artifacts` (alertas, bitacora)
12. ✅ `0001_01_01_000001_create_cache_table`
13. ✅ `0001_01_01_000002_create_jobs_table`

**Verificación de ejecución:**
```bash
cd c:\xampp\htdocs\api-tienda
php artisan migrate:status
```

---

### Paso 3: Seeders Básicos
**Status:** ✅ COMPLETADO

**Datos iniciales sembrados:**
| Tabla | Cantidad | Detalles |
|-------|----------|----------|
| Usuarios | 5 | 3 roles (dueno, encargado, cajero) + 2 extra |
| Categorías | 8 | Rubros estándar tienda abarrotes |
| Proveedores | 5 | Proveedores diversificados |
| Productos | 9 | Mix stock: 1 con stock bajo (<5), otros normales |
| Lotes | 0 | Será llenado por entradas de inventario |

**Usuarios creados:**
```
✓ Dueno Tienda (dueno)
✓ Encargado General (encargado)
✓ Cajero Principal (cajero)
✓ Mr. Grover Rosenbaum (cajero)
✓ Jace Auer (cajero)
```

**Producto con stock bajo (validación de requisito):**
```
✓ Cloro 1L: 4 unidades (< 5)
```

**Para ejecutar seeders:**
```bash
cd c:\xampp\htdocs\api-tienda
php artisan db:seed
```

---

## ✅ Validación de Completitud

| Requisito | Status | Evidencia |
|-----------|--------|-----------|
| 9 tablas diseñadas | ✅ | ERD con 9 tablas definidas |
| Migraciones en orden exacto | ✅ | Todas ejecutadas sin errores |
| 3 roles en usuarios | ✅ | dueno, encargado, cajero |
| 5 categorías | ✅ | 8 categorías (excede expectativa) |
| 8 productos | ✅ | 9 productos (excede expectativa) |
| Stock bajo visible | ✅ | Cloro 1L: 4 unidades |
| Fechas de caducidad en BD | ✅ | Campo fecha_caducidad en productos |
| Seeders funcionales | ✅ | php artisan db:seed ejecuta sin errores |

---

## 🔧 Estado del Proyecto

**Base de datos:** SQLite (`database/database.sqlite`)  
**Framework:** Laravel 12.0  
**PHP:** ^8.2  
**Migraciones:** 13/13 ejecutadas ✅  
**Seeders:** 3/3 ejecutados ✅  

---

## ➡️ Próximas Acciones (Semana 2 - Paralelo)

1. **Melani (Backend)** — En paralelo: crear rutas protegidas y middleware CheckRole.
2. **Luis (Diseño)** — En paralelo: diseñar layouts y paleta de colores.
3. **Liz (Frontend)** — Esperar: a que Angel y Luis terminen.
4. **Oscar (QA)** — Esperar: a que el sistema tenga UI funcional.

---

## 🎯 Checklist de Entrega

- [x] ERD con 9 tablas y relaciones correctas
- [x] Migraciones ejecutadas sin errores (php artisan migrate --force)
- [x] Seeders con 3 usuarios (por rol), 5+ categorías, 8+ productos
- [x] Productos con stock bajo marcados
- [x] Fechas de caducidad en productos
- [x] Base de datos compilada y lista para operación

**Resultado:** 🎉 **LISTO PARA PRODUCCIÓN DE SEMANA 2**

---

**Documento creado:** 28 Abril 2026  
**Responsable:** Angel  
**Contactar a:** si hay dependencias bloqueando a otros roles