# API RESTful - Gestión de Productos y Divisas

API RESTful desarrollada con **Laravel 12** para la gestión de productos con soporte para múltiples divisas. Diseñada bajo arquitectura limpia y principios **SOLID**.

Documentación interactiva disponible en **Swagger UI**: [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)

---

## ⚙️ Requisitos

* **PHP** >= 8.2 (con extensiones pdo, pdo_sqlite o pdo_mysql)
* **Composer** >= 2.x
* **SQLite** (por defecto) o **MySQL / MariaDB** (ej. XAMPP)

---

## 🚀 Instalación y Configuración

```bash
# 1. Clonar el repositorio y entrar a la carpeta
git clone https://github.com/sam1dc/api-gestion-productos.git
cd api-gestion-productos

# 2. Instalar dependencias
composer install

# 3. Configurar archivo de entorno y generar clave
cp .env.example .env
php artisan key:generate
```

### Configuración de Base de Datos (Elige una opción):

#### 🔹 Opción A: SQLite (Recomendada - Sin configuración externa)
No requiere instalar ni abrir ningún servidor de base de datos:
```bash
# Crear el archivo de base de datos
touch database/database.sqlite

# Ejecutar migraciones y poblar datos iniciales
php artisan migrate --seed
```
*(El archivo `.env` ya viene configurado con `DB_CONNECTION=sqlite` por defecto)*.

#### 🔹 Opción B: MySQL / MariaDB (XAMPP)
1. Inicia **MySQL** en el panel de control de XAMPP.
2. Crea la base de datos `api_gestion_productos` (o desde phpMyAdmin en `http://localhost/phpmyadmin`).
3. Modifica tu archivo `.env`:
   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=api_gestion_productos
   DB_USERNAME=root
   DB_PASSWORD=
   ```
4. Ejecuta migraciones y datos de prueba:
   ```bash
   php artisan migrate --seed
   ```

---

## 🖥️ Ejecución del Servidor

```bash
php artisan serve --port=8000
```

* **API Base:** `http://localhost:8000/api`
* **Swagger UI:** `http://localhost:8000/api/documentation` *(o visitando directamente `http://localhost:8000`)*

---

## ☁️ Despliegue en Render (con Docker)

El proyecto incluye configuración lista para desplegar en [Render.com](https://render.com):

1. En Render, selecciona **New +** > **Web Service**.
2. Conecta tu repositorio de GitHub: `sam1dc/api-gestion-productos`.
3. Render detectará automáticamente el **`Dockerfile`**.
4. Haz clic en **Deploy Web Service** (Plan Free).

El contenedor se encargará automáticamente de:
- Escuchar en el puerto dinámico de Render (`$PORT`).
- Crear e inicializar la base de datos SQLite con sus tablas y datos de prueba (`migrate --seed`).
- Servir la API y la documentación **Swagger UI** pública y lista para probar.

---

## 📡 Endpoints de la API

Todos los endpoints retornan datos en formato **JSON**.

| Método | Endpoint | Descripción | Parámetros Query / Body |
|:---:|---|---|---|
| `GET` | `/api/currencies` | Listar catálogo de divisas y tasas de cambio | - |
| `GET` | `/api/products` | Listar productos paginados | `?page=1&per_page=15` |
| `POST` | `/api/products` | Crear un producto con su divisa base | `name`, `price`, `currency_id`, `description`, `tax_cost`, `manufacturing_cost` |
| `GET` | `/api/products/{id}` | Obtener detalle de un producto por ID | - |
| `PUT` | `/api/products/{id}` | Actualizar datos de un producto | Campos a modificar (`name`, `price`, etc.) |
| `DELETE` | `/api/products/{id}` | Eliminar lógicamente un producto (Soft Delete) | - |
| `GET` | `/api/products/{id}/prices` | Listar precios del producto en distintas divisas | - |
| `POST` | `/api/products/{id}/prices` | Registrar o actualizar precio en una divisa | `currency_id`, `price` |

---

## 🏗️ Principios SOLID Aplicados

| Principio | Aplicación en el Proyecto |
|---|---|
| **S - Single Responsibility** | Capas desacopladas: **FormRequests** (validación), **Controllers** (HTTP), **Services** (lógica de negocio), **Repositories** (acceso Eloquent), **Resources** (transformación JSON). |
| **O - Open/Closed** | Nuevos métodos de consulta o almacenes de datos se integran mediante interfaces sin alterar la lógica existente. |
| **L - Liskov Substitution** | `ProductRepository` y `ProductPriceRepository` cumplen al 100% los contratos de sus interfaces, permitiendo sustituciones limpias. |
| **I - Interface Segregation** | Interfaces divididas y cohesivas: `ProductRepositoryInterface` y `ProductPriceRepositoryInterface`. |
| **D - Dependency Inversion** | Controladores y Servicios dependen de abstracciones (interfaces inyectadas mediante `RepositoryServiceProvider`). |

---

## 🧪 Pruebas Automatizadas

El proyecto cuenta con una suite completa de pruebas funcionales automatizadas:

```bash
php artisan test
```

Verifica creación, lectura, actualización, eliminación lógica, validaciones (código 422), gestión de precios multi-divisa y paginación.
