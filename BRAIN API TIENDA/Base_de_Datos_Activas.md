# Base de Datos Activas

#bases-de-datos #triggers #modelo-ECA #activas #autonomas

---

## ¿Qué es una Base de Datos Activa?

Un **sistema de base de datos activas** es un SGBD que contiene un subsistema para definir y gestionar **reglas de producción** (reglas activas).

> A diferencia de una BD pasiva (que solo responde consultas), una BD activa puede **reaccionar automáticamente** ante eventos.

---

## Modelo ECA (Evento – Condición – Acción)

Las reglas activas siguen el modelo **ECA**:

```
Evento detectado → ¿Condición cumplida? → Ejecutar Acción
```

| Componente | Descripción |
|------------|-------------|
| **Evento** | Lo que dispara la regla (INSERT, UPDATE, DELETE, tiempo, etc.) |
| **Condición** | Predicado que se evalúa cuando ocurre el evento |
| **Acción** | Lo que se ejecuta si la condición es verdadera |

La ejecución es controlada por un **motor de reglas** autónomo que detecta eventos y planifica la ejecución de reglas.

---

## Triggers — Reglas Activas Simples

Los **disparadores (triggers)** son la implementación más común del modelo ECA en los SGBD relacionales.

- Están basados en el modelo ECA
- Se definen sobre tablas o vistas
- Reaccionan ante operaciones DML: `INSERT`, `UPDATE`, `DELETE`

> [[Transacciones_y_Control_de_Concurrencia]] — Los triggers se ejecutan dentro del contexto de una transacción.

---

## Sistemas Semiautónomos vs. Autónomos

### Sistema Semiautónomo

Tiene capacidad de autogestionar **algunos** procesos (optimización de consultas, asignación de recursos, recuperación ante fallos), pero **requiere intervención humana** en decisiones críticas o complejas.

**Objetivo**: reducir la carga operativa del DBA sin eliminar su rol de supervisión.

Características:
- Optimización automática de consultas
- Recuperación ante fallos con supervisión
- Ajuste de recursos con aprobación humana

Ventajas:
- Menor carga operativa
- Mayor disponibilidad
- Reducción de errores humanos en tareas rutinarias

Limitaciones:
- Sigue dependiendo del DBA para decisiones complejas
- Puede tener conflictos entre automatización y políticas personalizadas

---

### Sistema Autónomo

Se administra **completamente por sí mismo** sin intervención humana directa, usando IA, aprendizaje automático y automatización avanzada.

**Objetivo**: reducir al mínimo el papel del DBA en tareas operativas rutinarias.

> Un **Sistema Autónomo (AS)** en redes también se refiere a una red grande con política de enrutamiento unificada — componente fundamental de Internet.

Características:
- Autoconfiguración
- Autooptimización
- Autoreparación
- Autoseguridad

Beneficios:
- Eliminación casi total de tareas manuales
- Alta disponibilidad y rendimiento constante
- Menor costo operativo a largo plazo

Retos:
- Complejidad de implementación
- Confianza en decisiones automatizadas
- Auditoría y control

---

## Generación de BD — Evolución

```
BD Pasivas → BD Activas (triggers/reglas ECA) → BD Semiautónomas → BD Autónomas
```

---

## Conexiones con otros temas

- [[Transacciones_y_Control_de_Concurrencia]] — Los triggers se ejecutan dentro de transacciones
- [[Cursores]] — Se pueden usar cursores dentro de triggers para procesar filas
- [[Funciones_en_MySQL]] — Las funciones pueden ser invocadas desde un trigger
