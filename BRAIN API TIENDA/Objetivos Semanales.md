[21/4, 10:40 p. m.] +52 768 104 1506: @⁨Angel⁩ :INT-02 — ARQUITECTO DE BASE DE DATOS

Objetivo 1
Diseñar el Diagrama ERD completo del sistema
Qué debe entregar exactamente:
--warp opensource
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
[21/4, 10:50 p. m.] +52 768 104 1506: # 🚨 ORDEN DE ARRANQUE — HOY
## Tienda de Abarrotes | Sprint 1

---

## LA CADENA DE DEPENDENCIAS — Por qué importa el orden

```
Angel (DB) ──────┐
                 ▼
Melani (Backend) ──► Liz (Frontend) ──► Oscar (QA)
                 ▲
Luis (UI) ───────┘
```

> Si Angel no levanta la BD hoy, Melani no puede probar Auth.
> Si Melani no termina Auth, Liz no puede construir el Login.
> Si Luis no define el layout, Liz no tiene dónde poner las vistas.
> Oscar entra cuando los demás tienen algo que probar.

---

## 🥇 PRIORIDAD 1 — EMPIEZA AHORA MISMO
### Angel — DB & Arquitecto de Datos

Angel es el más importante del día de hoy. **Todo el equipo está bloqueado sin él.**

**Lo que hace hoy sin parar:**

**Paso 1 — Antes de tocar Laravel:**
Abrir dbdiagram.io y dibujar el ERD con las 9 tablas. Esto no es opcional, es lo primero porque obliga a pensar antes de escribir código.

**Paso 2 — Migraciones en este orden exacto:**
```
1. categorias          (no depende de nadie)
2. proveedores         (no depende de nadie)
3. usuarios            (no depende de nadie)
4. productos           (depende de categorias y proveedores)
5. lotes_producto      (depende de productos)
6. entradas_inventario (depende de productos, usuarios, proveedores)
7. salidas_inventario  (depende de productos, usuarios)
8. ventas              (depende de usuarios)
9. detalles_venta      (depende de ventas y productos)
```

**Paso 3 — Seeders básicos hoy mismo:**
- 3 usuarios (dueño, encargado, cajero)
- 5 categorías reales
- 8 productos de tienda de abarrotes

**Angel no se mueve a otra cosa hasta que `php artisan migrate --seed` corra sin errores.**

---

## 🥈 PRIORIDAD 2 — EMPIEZA EN PARALELO CON ANGEL
### Melani — Backend Lead

Melani puede arrancar sin esperar a Angel porque su primer objetivo no necesita la BD todavía.

**Lo que hace hoy:**

**Paso 1 — Crear el proyecto Laravel ahora:**
```bash
composer create-project laravel/laravel tienda-abarrotes
```
Configurar `.env` con los datos de conexión a MySQL de Laragon. Confirmar que `localhost:8000` carga.

**Paso 2 — Mientras Angel termina las migraciones:**
Escribir el Middleware `CheckRole.php` y los grupos de rutas en `web.php`. Puede usar usuarios hardcodeados temporalmente para probar.

**Paso 3 — Cuando Angel suba los seeders:**
Conectar Auth con la BD real y probar login con los 3 usuarios del seeder.

**Melani y Angel deben estar comunicándose constantemente hoy. Son el núcleo.**

---

## 🥉 PRIORIDAD 3 — EMPIEZA HOY TAMBIÉN, EN PARALELO
### Luis — UI / Documentación

Luis no necesita ni BD ni Laravel para su primer objetivo. **No tiene excusa para esperar.**

**Lo que hace hoy:**

**Paso 1 — Abrir Figma o Canva ahora mismo:**
Definir paleta de colores y tipografía. Pensar en que lo va a usar el dueño de la tienda, no un ingeniero.

**Paso 2 — Dibujar los 2 wireframes:**
- Pantalla de Login
- Dashboard del dueño

**Paso 3 — Cuando Melani tenga Laravel corriendo:**
Crear `layouts/app.blade.php` con el sidebar condicional por rol e integrar Tailwind CSS vía CDN.

**Luis debe tener el sistema de diseño listo antes de que Liz empiece a programar vistas. Eso es hoy.**

---

## ⏳ PRIORIDAD 4 — ESPERA HASTA QUE ANGEL Y LUIS TERMINEN
### Liz — Frontend Developer

Liz es la que más depende de los demás. **No puede construir pantallas sin BD con datos ni sin layout.**

**Lo que hace mientras espera:**
Estudiar cómo funciona la redirección por rol en Laravel. Preparar los archivos blade vacíos. Revisar el ERD de Angel para entender qué datos va a mostrar.

**Lo que hace en cuanto Angel y Luis terminen:**
```
1. Login funcional conectado a BD real
2. Los 3 dashboards con datos reales de ::count()
3. Lista de productos con colores por stock y caducidad
```

**Liz no debe inventar datos. Trabaja con los seeders de Angel.**

---

## 🔍 PRIORIDAD 5 — ENTRA MAÑANA
### Oscar — QA / Tester

Oscar no puede probar lo que no existe. Hoy tiene una sola misión:

**Lo que hace hoy:**
Preparar la tabla de casos de prueba con los 10 escenarios definidos, con las columnas: escenario, resultado esperado, resultado real, estado. Los deja listos para ejecutar mañana.

**Lo que hace mañana cuando el equipo tenga algo funcional:**
Instala el proyecto desde cero siguiendo el README de Luis y ejecuta todos los casos de prueba uno por uno.

---

## 📋 RESUMEN VISUAL DE HOY

```
AHORA MISMO
├── Angel  → Crear ERD + Migraciones + Seeders
├── Melani → Crear proyecto Laravel + Middleware
└── Luis   → Wireframes + Sistema de diseño

ESTA TARDE (cuando Angel tenga BD lista)
├── Melani → Conectar Auth con BD real
└── Luis   → Crear layout Blade

MAÑANA TEMPRANO
├── Liz    → Login + Dashboards + Lista de productos
└── Oscar  → Instalar desde README + Ejecutar pruebas
```

---

## 🔴 REGLA DE HOY

> **Angel y Melani no pueden terminar el día sin tener estos 2 puntos confirmados en Trello:**
> - `php artisan migrate --seed` corriendo sin errores ← **Angel**
> - `localhost:8000` con Laravel funcionando ← **Melani**
>
> Si eso no está listo hoy, mañana Liz y Oscar no tienen nada que hacer y el sprint se cae el jueves.

**¿Angel ya tiene Laragon instalado y corriendo en su máquina? Ese es el primer blocker que hay que resolver en los próximos 10 minutos.**
[21/4, 10:57 p. m.] +52 768 104 1506: No entendi bien pero se puede hacer remotamente en github 1. Angel   → Crear el repositorio en GitHub ahora
2. Angel   → Invitar a Melani, Luis, Liz y Oscar
3. Melani  → Clonar el repo y subir el proyecto Laravel
4. Todos   → Clonar el repositorio en su propia máquina
5. Todos   → Crear su rama: git checkout -b feature/su-nombre
6. Todos   → Unirse al grupo de WhatsApp o Discord del equipo