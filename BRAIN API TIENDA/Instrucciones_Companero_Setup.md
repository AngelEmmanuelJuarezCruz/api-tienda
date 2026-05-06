# Instrucciones para preparar el proyecto en otra PC

Estas instrucciones son para que cada compañero pueda clonar el repositorio y dejar la base de datos local lista.

## 1) Clonar o actualizar el repositorio

```bash
git clone <URL_DEL_REPO>
# o si ya lo tiene
git pull
```

## 2) Crear el archivo .env

```bash
copy .env.example .env
```

Editar el archivo `.env` y poner la conexion local:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=api_tienda
DB_USERNAME=root
DB_PASSWORD=
```

## 3) Instalar dependencias de PHP

```bash
composer install
```

## 4) Generar la APP_KEY

```bash
php artisan key:generate
```

## 5) Crear la base en MySQL

Si el comando `mysql` no funciona, usa el que viene con XAMPP.

```bash
"C:\\xampp\\mysql\\bin\\mysql.exe" -u root -e "CREATE DATABASE IF NOT EXISTS api_tienda;"
```

## 6) Ejecutar migraciones y seeders

```bash
php artisan migrate
php artisan db:seed
```

## 7) Verificar que todo quede listo

```bash
php artisan migrate:status
```

Si todas las migraciones aparecen como `Ran`, la base ya quedo lista.
