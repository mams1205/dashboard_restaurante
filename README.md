## Software desarrollado para el restaurante ANTESALA en QRO perteneciente al grupo10

# 📊 Dashboard de Ventas - Restaurante

Este repositorio contiene un sistema de dashboard web desarrollado en **PHP**, **HTML** y **Chart.js**, diseñado para visualizar información clave sobre las **ventas de un restaurante**.

La información mostrada en el dashboard se mantiene **actualizada en tiempo real**, gracias a un script en **Python** que se ejecuta automáticamente y transfiere datos desde una base de datos **SQL Server** hacia una base **MySQL** usada por la aplicación web.

---

## ✅ Funcionalidades del Dashboard

- Mostrar **total de ventas del día, semana y mes**.
- Visualizar **cancelaciones del día**.
- Gráfico interactivo con los **alimentos más vendidos**.
- Acceso rápido a los datos a través de tarjetas informativas.

---

## ⚙️ Tecnologías Utilizadas

### Backend y Frontend
- **PHP** – Para consultas al servidor y renderizado de datos.
- **Chart.js** – Para crear gráficas interactivas.
- **HTML5 / CSS3** – Para estructurar y dar estilo al dashboard.
- **MySQL** – Base de datos que almacena la información de ventas procesada.

### Automatización de Datos
- **Python** – Script que ejecuta consultas a **SQL Server** y exporta los datos a **MySQL**.
- **pandas**, **pyodbc**,  – Librerías para extracción, transformación y carga (ETL).

---

## 🔄 Flujo de Trabajo Automatizado

1. El **script en Python** se ejecuta en intervalos definidos por el usuario (manual o mediante un programador de tareas).
2. Este script:
   - Se conecta a la base de datos **SQL Server** (fuente original de ventas).
   - Extrae y transforma los datos relevantes.
   - Los carga en la base **MySQL** usada por el dashboard.
3. El dashboard en **PHP** lee los datos actualizados desde MySQL y los muestra en tiempo real.

---

