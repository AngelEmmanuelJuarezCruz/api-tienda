# Funciones en MySQL

#bases-de-datos #MySQL #funciones #stored-functions

---

## ¿Qué es una Función en MySQL?

Una **función** es una rutina almacenada que recibe parámetros de entrada, realiza operaciones y **devuelve un único valor**. A diferencia de los procedimientos almacenados, las funciones siempre retornan un resultado.

---

## Sintaxis General

```sql
CREATE
  [DEFINER = { user | CURRENT_USER }]
  FUNCTION nombre_funcion ([parametro tipo, ...])
  RETURNS tipo_de_dato
  [caracteristica ...]
BEGIN
  -- cuerpo de la función
  RETURN valor;
END
```

### Parámetros de entrada

```sql
FUNCTION mi_funcion(param1 VARCHAR(50), param2 INT)
```

- Solo admite parámetros de **entrada** (no OUT ni INOUT como los procedimientos)
- Se declaran con nombre y tipo de dato MySQL válido

### Resultado de salida

- Se declara con `RETURNS tipo` al inicio
- Se devuelve con `RETURN valor` dentro del cuerpo

```sql
RETURNS INT UNSIGNED
...
RETURN total;
```

---

## Características de la Función

Se declaran después de `RETURNS tipo`:

| Característica | Descripción |
|----------------|-------------|
| `DETERMINISTIC` | Siempre devuelve el mismo resultado con los mismos parámetros |
| `NOT DETERMINISTIC` | El resultado puede variar aunque los parámetros sean iguales *(default)* |
| `CONTAINS SQL` | Contiene SQL pero no lee ni escribe datos (ej: `SET @x = 1`) |
| `NO SQL` | No contiene sentencias SQL |
| `READS SQL DATA` | Lee datos con `SELECT`, no los modifica |
| `MODIFIES SQL DATA` | Modifica datos con `INSERT`, `UPDATE` o `DELETE` |

---

## Uso del DELIMITER

Necesario para que MySQL no interprete el `;` interno como fin de la sentencia:

```sql
DELIMITER $$

CREATE FUNCTION mi_funcion(...)
  RETURNS INT
  READS SQL DATA
BEGIN
  -- cuerpo
  RETURN resultado;
END $$

DELIMITER ;
```

---

## Ejemplo Completo

**Objetivo**: Función que recibe el nombre de una gama y devuelve el número de productos en esa gama.

```sql
DELIMITER $$

DROP FUNCTION IF EXISTS contar_productos$$

CREATE FUNCTION contar_productos(gama VARCHAR(50))
  RETURNS INT UNSIGNED
  READS SQL DATA
BEGIN
  -- Paso 1: Declarar variable local
  DECLARE total INT UNSIGNED;

  -- Paso 2: Contar productos de la gama
  SET total = (
    SELECT COUNT(*) 
    FROM producto 
    WHERE producto.gama = gama
  );

  -- Paso 3: Devolver resultado
  RETURN total;
END $$

DELIMITER ;

-- Uso:
SELECT contar_productos('Herramientas');
```

---

## Funciones vs. Procedimientos Almacenados

| Aspecto | Función | Procedimiento |
|---------|---------|---------------|
| Retorna valor | ✅ Siempre (un valor) | ❌ No obligatorio |
| Se usa en SELECT | ✅ Sí | ❌ No |
| Parámetros OUT | ❌ No | ✅ Sí |
| Puede modificar datos | ⚠️ Solo con `MODIFIES SQL DATA` | ✅ Sí |

---

## Conexiones con otros temas

- [[Cursores]] — Los cursores se pueden usar dentro del cuerpo de una función
- [[Transacciones_y_Control_de_Concurrencia]] — Las funciones pueden ser llamadas dentro de una transacción
- [[Base_de_Datos_Activas]] — Los triggers pueden invocar funciones como parte de su acción ECA
