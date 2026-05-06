# Transacciones y Control de Concurrencia

#bases-de-datos #transacciones #ACID #concurrencia

---

## ¿Qué es una Transacción?

Una **transacción** es una unidad de ejecución de un programa que puede consistir en varias operaciones de acceso a la base de datos. Está delimitada por constructoras como:

```sql
BEGIN TRANSACTION
-- operaciones
END TRANSACTION
```

> Los SGBDs son sistemas **concurrentes**: admiten la ejecución simultánea de múltiples consultas, por lo que se necesitan mecanismos para preservar la integridad de los datos.

---

## Propiedades ACID

| Propiedad | Descripción |
|-----------|-------------|
| **Atomicidad** | La transacción ocurre completamente o no ocurre. Todo o nada. |
| **Consistencia** | La ejecución aislada preserva la consistencia de la BD. |
| **Aislamiento** | Cada transacción se ejecuta como si fuera la única en el sistema. |
| **Durabilidad** | Los cambios de una transacción exitosa persisten permanentemente. |

### Atomicidad — Casos a considerar
- **Consultas unitarias**: incluso para una sola consulta SQL, la ejecución concurrente puede ser incorrecta sin precauciones.
- **Operación abortada**: por división por cero, falta de privilegios, o para evitar bloqueos.

### Aislamiento — Niveles

De menor a mayor nivel de aislamiento:

1. **Lectura no comprometida** — Asegura solo que no se lean datos corruptos físicamente. Menor nivel.
2. **Lectura comprometida** — Solo se permiten lecturas de datos ya confirmados.
3. **Lectura repetible** — La misma fila leída varias veces dentro de la misma transacción da el mismo resultado.
4. **Secuenciable** — Las transacciones se aíslan completamente entre sí. Mayor nivel.

> ⚠️ A mayor aislamiento = mayor precisión, pero menor concurrencia.

---

## Estados de una Transacción

```
Activa → Parcialmente comprometida → Comprometida ✅
   ↓
Fallida → Abortada (rollback) → Se reinicia o cancela ❌
```

| Estado | Descripción |
|--------|-------------|
| **Activa** | Durante su ejecución normal |
| **Parcialmente comprometida** | Después de ejecutar su última instrucción |
| **Fallida** | Imposible continuar la ejecución normal |
| **Abortada** | Retrocedida; la BD vuelve al estado anterior |

---

## Tipos de Transacciones

- **Implícitas** (autocommit): cada instrucción es automáticamente una transacción.
- **Explícitas** (delimitadas): el programador define inicio y fin manualmente.

---

## Sintaxis — Transacciones Explícitas

```sql
BEGIN TRANSACTION

  -- Instrucción 1
  SAVE TRANSACTION punto_guardado

  -- Instrucción 2
  ROLLBACK TRANSACTION punto_guardado  -- deshace hasta el savepoint

  -- Instrucción n
COMMIT TRANSACTION
```

| Comando | Función |
|---------|---------|
| `BEGIN TRANSACTION` | Inicia la transacción |
| `SAVE TRANSACTION sp` | Crea un punto de guardado parcial |
| `ROLLBACK TRANSACTION sp` | Deshace hasta el savepoint (o todo si no se especifica) |
| `COMMIT TRANSACTION` | Confirma todos los cambios |

---

## Ejemplo Completo

**Escenario**: Incrementar en 1% las comisiones del 15% y 16% en la tabla `roysched`. Si alguna no existe, no se actualiza ninguna.

```sql
BEGIN TRAN actualiza_comisiones

USE pubs

IF EXISTS (
  SELECT titles.title, roysched.royalty 
  FROM titles, roysched
  WHERE titles.title_id = roysched.title_id 
    AND roysched.royalty = 16
)
  UPDATE roysched SET royalty = 17 WHERE royalty = 16
ELSE
  ROLLBACK TRAN actualiza_comisiones

IF EXISTS (
  SELECT titles.title, roysched.royalty 
  FROM titles, roysched
  WHERE titles.title_id = roysched.title_id 
    AND roysched.royalty = 15
)
BEGIN
  UPDATE roysched SET royalty = 16 WHERE royalty = 15
  COMMIT TRAN actualiza_comisiones
END
ELSE
  ROLLBACK TRAN actualiza_comisiones
```

---

## Conexiones con otros temas

- [[Cursores]] — Los cursores se usan dentro de transacciones para recorrer conjuntos de filas
- [[Base_de_Datos_Activas]] — Los triggers (disparadores) pueden dispararse dentro de una transacción
- [[Funciones_en_MySQL]] — Las funciones pueden ser llamadas dentro de una transacción
