# 🔍 Guía de Queries Comunes — Base de Datos

Ejemplos de queries que usarán Melani y Liz frecuentemente. Ejecuta en `php artisan tinker`.

---

## 👥 Queries de Usuarios

```php
// Obtener todos los usuarios activos
User::activos()->get();

// Obtener solo dueños
User::duenos()->get();

// Obtener solo cajeros
User::cajeros()->get();

// Obtener encargados
User::encargados()->get();

// Obtener un usuario con todas sus ventas
$user = User::with('ventas')->find(1);
$user->ventas;  // Array de ventas del usuario

// Total de ventas de un usuario
$user->ventas()->count();

// Suma de ventas de un usuario
$user->ventas()->sum('monto_final');
```

---

## 📦 Queries de Productos

```php
// Todos los productos activos
Producto::activos()->get();

// Productos con stock bajo
Producto::stockBajo()->get();

// Productos próximos a caducar (7 días)
Producto::proximosACaducar()->get();

// Productos próximos a caducar (14 días)
Producto::proximosACaducar(14)->get();

// Productos de una categoría
Producto::porCategoria(1)->get();

// Productos de un proveedor
Producto::porProveedor(1)->get();

// Productos ordenados por stock bajo
Producto::ordenadosPorStock()->get();

// Producto con su categoría y proveedor
Producto::with(['categoria', 'proveedor'])->find(1);

// Todos los productos con su categoría
Producto::with('categoria')->get();

// Productos con más de 100 en stock
Producto::where('stock_actual', '>', 100)->get();

// Conteo de productos
Producto::count();

// Suma de valor en inventario
Producto::selectRaw('SUM(stock_actual * precio_venta) as valor_inventario')->first();
```

---

## 🏷️ Queries de Categorías

```php
// Categoría con todos sus productos
Categoria::with('productos')->find(1);

// Total de productos en una categoría
Categoria::find(1)->productos()->count();

// Categoría con productos que tienen stock bajo
Categoria::with(['productos' => function($query) {
    $query->stockBajo();
}])->find(1);
```

---

## 🚗 Queries de Proveedores

```php
// Proveedor con todos sus productos
Proveedor::with('productos')->find(1);

// Proveedor con todas sus entradas de inventario
Proveedor::with('entradasInventario')->find(1);

// Total de productos de un proveedor
Proveedor::find(1)->productos()->count();
```

---

## 💰 Queries de Ventas

```php
// Venta con todos sus detalles
Venta::with('detallesVenta')->find(1);

// Venta con usuario, detalles y productos
Venta::with(['usuario', 'detallesVenta.producto'])->find(1);

// Total de ventas completadas
Venta::where('estado', 'completada')->count();

// Suma de todas las ventas
Venta::where('estado', 'completada')->sum('monto_final');

// Ventas de hoy
Venta::whereDate('created_at', today())->get();

// Ventas de un usuario específico
User::find(1)->ventas()->get();

// Ventas con detalles y productos
Venta::with('detallesVenta.producto')->where('estado', 'completada')->get();
```

---

## 📥 Queries de Entradas de Inventario

```php
// Entrada con producto y proveedor
EntradaInventario::with(['producto', 'proveedor', 'usuario'])->find(1);

// Entradas de hoy
EntradaInventario::whereDate('created_at', today())->get();

// Entradas de un producto
EntradaInventario::where('producto_id', 1)->get();

// Total de inventario recibido en el mes
EntradaInventario::whereMonth('created_at', now()->month)->sum('cantidad');
```

---

## 📤 Queries de Salidas de Inventario

```php
// Salida con producto y usuario
SalidaInventario::with(['producto', 'usuario'])->find(1);

// Salidas de hoy
SalidaInventario::whereDate('created_at', today())->get();

// Salidas por motivo "caducado"
SalidaInventario::where('motivo', 'caducado')->get();

// Salidas por motivo "dañado"
SalidaInventario::where('motivo', 'dañado')->get();

// Total de productos caducados
SalidaInventario::where('motivo', 'caducado')->sum('cantidad');
```

---

## 🔐 Queries por Rol (para Auth)

```php
// Obtener usuario por email y validar contraseña
$user = User::where('email', 'dueno@tienda.test')->first();
Hash::check('password', $user->password);  // true si es correcto

// Verificar si usuario es dueño
$user->rol === 'dueno';

// Obtener conteos para dashboard del dueño
[
    'usuarios' => User::count(),
    'productos' => Producto::count(),
    'ventas_hoy' => Venta::whereDate('created_at', today())->count(),
    'ventas_hoy_total' => Venta::whereDate('created_at', today())->sum('monto_final'),
    'stock_bajo' => Producto::stockBajo()->count(),
];
```

---

## 🔧 Tips Útiles

### Contar registros
```php
Producto::count();
Producto::where('activo', true)->count();
```

### Filtrar con múltiples condiciones
```php
Producto::where('activo', true)
         ->where('stock_actual', '<', 5)
         ->get();
```

### Ordernar resultados
```php
Producto::orderBy('nombre', 'asc')->get();
Producto::orderBy('stock_actual', 'asc')->get();  // Stock bajo primero
```

### Limitar resultados
```php
Producto::limit(10)->get();
Producto::offset(10)->limit(10)->get();  // Página 2 (si cada página = 10)
```

### Paginar resultados
```php
Producto::paginate(15);  // 15 por página
```

### Select solo ciertas columnas
```php
Producto::select('id', 'nombre', 'stock_actual')->get();
```

### Buscar dentro de columnas
```php
Producto::where('nombre', 'like', '%Cloro%')->get();
```

---

## 📋 Reset de BD (si algo sale mal)

```bash
# Opción 1: Resetear y resembrar
php artisan migrate:refresh --seed

# Opción 2: Usar script especial
php artisan tinker
>>> include 'reset_db.php'
```

---

## 🚨 Troubleshooting

**"No such table: productos"**
```bash
php artisan migrate
```

**"Relación no existe"**
- Verifica que el modelo tenga el método `public function()` definido
- Verifica que las claves foráneas sean correctas

**"Modelo no encontrado"**
```bash
# Verifica que el modelo esté en app/Models/
ls app/Models/
```

---

**Actualizado:** 28 Abril 2026  
**Mantentendor:** Angel (DB Lead)