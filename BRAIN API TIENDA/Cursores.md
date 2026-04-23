# Cursores en MySQL

#bases-de-datos #MySQL #cursores #procedimientos-almacenados

---

## ¿Qué es un Cursor?

Un **cursor** permite almacenar un conjunto de filas resultado de una consulta en una estructura de datos que se puede **recorrer de forma secuencial**, fila por fila.

> Se usan principalmente dentro de [[Funciones_en_MySQL|funciones]] y procedimientos almacenados.

---

## Propiedades de los Cursores en MySQL

| Propiedad | Descripción |
|-----------|-------------|
| **Asensitive** | El servidor puede o no hacer una copia de la tabla resultado |
| **Read Only** | Solo lectura — no permiten actualizar datos |
| **Nonscrollable** | Solo avanzan en una dirección; no se pueden saltar filas |

### Orden de declaración en un procedimiento almacenado

```
1. Variables locales    (DECLARE var)
2. Cursores             (DECLARE cursor)
3. Manejadores de error (DECLARE HANDLER)
```

> ⚠️ El cursor debe declararse **antes** de los HANDLER y **después** de las variables locales.

---

## Operaciones con Cursores

```
DECLARE → OPEN → FETCH (loop) → CLOSE
```

### 1. DECLARE — Declarar el cursor

```sql
DECLARE nombre_cursor CURSOR FOR
  SELECT columna1, columna2 FROM tabla;
```

### 2. OPEN — Abrir el cursor

```sql
OPEN nombre_cursor;
```

### 3. FETCH — Obtener filas una a una

```sql
FETCH [NEXT] [FROM] nombre_cursor INTO variable1, variable2;
```

Cuando no quedan más filas se lanza el error `NOT FOUND` (`SQLSTATE '02000'`). Se debe manejar con:

```sql
DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
```

### 4. CLOSE — Cerrar el cursor

```sql
CLOSE nombre_cursor;
```

---

## Ejemplo Completo

**Escenario**: Recorrer la tabla `clientes` e insertar sus datos en otra tabla `test`.

```sql
DECLARE done INT DEFAULT FALSE;
DECLARE a VARCHAR(16);
DECLARE AB INT;

-- Declarar el cursor
DECLARE cur1 CURSOR FOR 
  SELECT id, data FROM clientes;

-- Manejador para cuando no haya más filas
DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

-- Abrir el cursor
OPEN cur1;

-- Recorrer todas las filas
WHILE done = FALSE DO
  FETCH cur1 INTO AB, a;
  IF done = FALSE THEN
    INSERT INTO test VALUES (a, AB);
  END IF;
END WHILE;

-- Cerrar el cursor
CLOSE cur1;
```

---

## Cuándo usar cursores

✅ Cuando necesitas procesar fila por fila con lógica compleja
✅ Dentro de procedimientos almacenados o funciones
✅ Cuando no es posible resolver el problema con una sola consulta SQL

❌ Evítalos si puedes usar operaciones set-based (SELECT, UPDATE masivo) — son más eficientes.

---

## Conexiones con otros temas

- [[Funciones_en_MySQL]] — Los cursores se usan dentro de funciones y stored procedures
- [[Transacciones_y_Control_de_Concurrencia]] — Un cursor puede operar dentro de una transacción
- [[Base_de_Datos_Activas]] — Los triggers pueden invocar procedimientos con cursores
