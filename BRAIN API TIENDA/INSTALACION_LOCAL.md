# 📖 Guía de Instalación — Tienda de Abarrotes

Para instalar y ejecutar el proyecto en tu computadora (desarrollo local con SQLite).

---

## ✅ Pre-requisitos

- **PHP** >= 8.2 (Laragon o similar)
- **Composer** instalado
- **Git** instalado
- **SQLite3** (incluido en PHP)

---

## 🚀 Pasos de Instalación

### 1. Clonar el Repositorio
```bash
git clone <URL-REPO>
cd api-tienda
```

### 2. Instalar Dependencias
```bash
composer install
```

### 3. Configurar Archivo `.env`
```bash
cp .env.example .env
```

**Verificar que contenga:**
```env
DB_CONNECTION=sqlite
```

Si no está, agrégalo. NO necesitas cambiar nada más (SQLite crea su propia BD).

### 4. Generar la Clave de la Aplicación
```bash
php artisan key:generate
```

### 5. **IMPORTANTE — Crear la Base de Datos Local**
Este paso crea tu propia copia de la BD con tablas y datos de prueba:

```bash
php artisan migrate:seed --force
```

Esto ejecuta:
- ✅ Todas las migraciones (crea tablas)
- ✅ Todos los seeders (inserta datos de prueba)

### 6. Iniciar el Servidor
```bash
php artisan serve
```

Accede a: **http://localhost:8000**

---

## 📊 Verificación de Instalación

Una vez que todo esté listo, verifica conteos de datos:

```bash
php artisan tinker
```

Dentro de tinker, ejecuta:
```php
DB::table('usuarios')->count();      // Debe mostrar: 5
DB::table('categorias')->count();    // Debe mostrar: 8
DB::table('productos')->count();     // Debe mostrar: 9
exit();
```

Si ves los números correctos, ¡todo OK! ✅

---

## 🔐 Usuarios de Prueba

| Email | Contraseña | Rol |
|-------|-----------|-----|
| dueno@tienda.test | password | Dueño |
| encargado@tienda.test | password | Encargado |
| cajero@tienda.test | password | Cajero |

*(Ajusta según el seeder actual)*

---

## 🆘 Problemas Comunes

### ❌ "Tabla no existe"
```bash
php artisan migrate:seed --force
```

### ❌ ".env no encontrado"
```bash
cp .env.example .env
php artisan key:generate
```

### ❌ "SQLSTATE[HY000]: General error"
Asegúrate de que:
1. `DB_CONNECTION=sqlite` en `.env`
2. La carpeta `database/` existe
3. PHP tiene permisos de escritura en `database/`

### ❌ "Composer: autoload not found"
```bash
composer install
```

---

## 📝 Notas Importantes

- **NO** hagas commit del archivo `database/database.sqlite` — cada dev debe generar el suyo
- Cada `php artisan migrate:seed` regenera la BD desde cero
- Para resetear datos de prueba: `php artisan migrate:refresh --seed`
- Si cambias migraciones, avisa al equipo para que todos corran `php artisan migrate`

---

## 🎯 ¿Qué Hacer Después?

1. Completa la instalación (pasos 1-6)
2. Verifica que veas http://localhost:8000 sin errores
3. Notifica a **Angel (DB Lead)** si hay problemas
4. Comienza a trabajar en tu feature

---

**Documento válido a partir de:** 28 Abril 2026  
**Mantenedor:** Angel (Base de Datos)  
**Última actualización:** 28 Abril 2026