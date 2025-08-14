# AUTORES
César González
David Tenguzñay
# Uso de la API EquipoReto con Postman

Esta guía te muestra cómo realizar operaciones **Crear (POST)**, **Actualizar (PUT)** y **Eliminar (DELETE)** sobre la relación Equipo-Reto (`Equipo_RetoSoluccionable`) usando Postman.

---

## 1. Crear asignación Equipo-Reto (POST)

- **URL:** `http://localhost/tu_ruta/public/api/equipoReto.php`
- **Método:** POST
- **Body (raw, JSON):**
```json
{
  "equipo_id": 1,
  "reto_id": 2
}
```
Esto asigna el equipo con ID 1 al reto con ID 2.

---

## 2. Actualizar asignación Equipo-Reto (PUT)

- **URL:** `http://localhost/tu_ruta/public/api/equipoReto.php`
- **Método:** PUT
- **Body (raw, JSON):**
```json
{
  "old_equipo_id": 1,
  "old_reto_id": 2,
  "equipo_id": 1,
  "reto_id": 3
}
```
Esto cambia la asignación del equipo 1 del reto 2 al reto 3.

---

## 3. Eliminar asignación Equipo-Reto (DELETE)

- **URL:** `http://localhost/tu_ruta/public/api/equipoReto.php`
- **Método:** DELETE
- **Body (raw, JSON):**
```json
{
  "equipo_id": 1,
  "reto_id": 3
}
```
Esto elimina la asignación del equipo 1 al reto 3.

---

## 4. Consultar asignaciones (GET)

- **Todas las asignaciones:**  
  - **URL:** `http://localhost/tu_ruta/public/api/equipoReto.php`
  - **Método:** GET

- **Asignación específica:**  
  - **URL:** `http://localhost/tu_ruta/public/api/equipoReto.php?equipo_id=1&reto_id=2`
  - **Método:** GET

---

## Notas

- Cambia `tu_ruta` por la ruta real de tu proyecto.
- En Postman, selecciona el método adecuado, el endpoint, y en "Body" elige "raw" y "JSON".
- Para **PUT** y **DELETE**, asegúrate de enviar el JSON en el body.
- La respuesta será un JSON indicando `success: true` o un mensaje de