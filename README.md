# Sistema de Gestión de Tareas Corregido

Este es un sistema avanzado de gestión de solicitudes y tareas técnicas desarrollado con **Laravel 11**, **Livewire 3** y **Tailwind CSS**. Permite la gestión de usuarios, asignación de tareas a equipos, seguimiento de estados y reportes detallados.

## 🚀 Características Principales

- **Dashboard Dinámico**: Visualización de KPIs y tareas en tiempo real.
- **Gestión de Tareas**: Creación de solicitudes con adjuntos (fotos y audio).
- **Categorías Personalizables**: Sistema dinámico para añadir categorías al vuelo.
- **Control de Acceso (RBAC)**: Diferenciación entre administradores y usuarios estándar.
- **Informes Avanzados**: Estadísticas de rendimiento, distribución por categorías y departamentos.
- **Archivo de Tareas**: Histórico de tareas completadas y canceladas.

## 🛠️ Requisitos Técnicos

Para ejecutar este proyecto localmente, asegúrate de tener instalado:

- **PHP 8.2** o superior
- **Composer**
- **Node.js & NPM**
- **Base de datos** (SQLite, MySQL o PostgreSQL)
- **Servidor Web** (Laravel Herd, Valet, Apache o Nginx)

## 📦 Instalación y Configuración

Sigue estos pasos para poner en marcha el proyecto por primera vez:

1. **Clonar el repositorio:**
   ```bash
   git clone <url-del-repositorio>
   cd tareas_corregido
   ```

2. **Instalar dependencias de PHP:**
   ```bash
   composer install
   ```

3. **Instalar dependencias de Frontend:**
   ```bash
   npm install
   ```

4. **Configurar el entorno:**
   - Copia el archivo de ejemplo: `cp .env.example .env`
   - Configura tus credenciales de base de datos en el archivo `.env`.

5. **Generar la clave de aplicación:**
   ```bash
   php artisan key:generate
   ```

6. **Ejecutar migraciones y seeders:**
   ```bash
   php artisan migrate --seed
   ```
   *(Nota: El seeder configurará las categorías base y usuarios iniciales si están definidos).*

7. **Vincular el almacenamiento de archivos:**
   ```bash
   php artisan storage:link
   ```
   *Crucial para que funcionen las fotos y audios adjuntos.*

8. **Compilar assets y arrancar el servidor:**
   En terminales separadas:
   ```bash
   npm run dev
   ```
   y
   ```bash
   php artisan serve
   ```

## 📂 Estructura de Adjuntos

Los archivos adjuntos (fotos y audio) se guardan en `storage/app/public/attachments`. Asegúrate de que esta carpeta tenga permisos de escritura.

## 👥 Roles de Usuario

- **Admin**: Acceso total al sistema, creación de usuarios, gestión de equipos y visualización de todos los informes.
- **Usuario Normal**: Solo puede ver sus propias tareas, los proyectos a los que está asignado y su equipo en modo lectura.

---
Desarrollado para la optimización de procesos técnicos y soporte.
