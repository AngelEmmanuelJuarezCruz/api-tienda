# Objetivos — Semana 2 (Resumido)

**Prioridad 1 — Angel (DB & Arquitecto de Datos)**
- Crear el ERD en dbdiagram.io con las 9 tablas: categorias, proveedores, usuarios, productos, lotes_producto, entradas_inventario, salidas_inventario, ventas, detalles_venta.
- Implementar migraciones en este orden exacto y asegurar que `php artisan migrate --seed` corra sin errores.
- Crear seeders básicos: 3 usuarios (dueño, encargado, cajero), 5 categorías reales, 8 productos (algunos con stock bajo y fechas de caducidad).

**Prioridad 2 — Melani (Backend Lead)**
- Crear el proyecto Laravel y dejarlo corriendo (configurar .env con MySQL de Laragon).
- Implementar middleware `CheckRole` y grupos de rutas con redirección por rol (usar usuarios hardcodeados para pruebas iniciales).
- Conectar Auth al subir los seeders y probar login con los 3 usuarios.

**Prioridad 3 — Luis (Diseño & Documentación)**
- Definir paleta de colores y tipografía (Figma/Canva).
- Diseñar wireframes: Login y Dashboard del dueño.
- Crear plantilla base `layouts/app.blade.php` con sidebar condicional por rol; integrar Tailwind vía CDN.
- Escribir manual de instalación para levantar el proyecto desde cero.

**Prioridad 4 — Liz (Frontend)**
- Preparar blades vacíos y estudiar la redirección por rol en Laravel.
- Implementar Login conectado a BD real con validación y redirección por rol.
- Crear 3 dashboards (dueño muestra totales reales) y la lista de productos con colores por stock/caducidad.

**Prioridad 5 — Oscar (QA / Tester)**
- Hoy: preparar la tabla de 10 casos de prueba (escenario, resultado esperado, resultado real, estado).
- Mañana: instalar el proyecto desde el README y ejecutar los 10 escenarios (incluye pruebas de acceso por rol, contraseñas incorrectas, accesos prohibidos y cierre de sesión).
- Verificar en BD: existencia de 3 roles, productos con stock bajo y unicidad de códigos de barras.

**Notas rápidas**
- Angel es crítico: no avanzar en otras cosas hasta que las migraciones y seeders funcionen.
- Comunicación constante entre Angel y Melani.
- Priorizar primero diseño básico y ERD antes de codificar (obligatorio para Angel).

---
*Documento resumido para uso del equipo — Semana 2.*