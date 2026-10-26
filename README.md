# 🛍️ API Gestión de Productos

API RESTful desarrollada con **Laravel 12** para la gestión de productos con soporte de múltiples divisas. Aplica los principios **SOLID** para un código limpio, escalable y mantenible.

---

## 📋 Tabla de Contenidos

- [Características](#-características)
- [Principios SOLID](#-principios-solid-aplicados)
- [Arquitectura del Proyecto](#-arquitectura-del-proyecto)
- [Modelo de Datos](#-modelo-de-datos)
- [Requisitos](#-requisitos)
- [Instalación](#-instalación)
- [Endpoints](#-endpoints)
- [Documentación Swagger](#-documentación-swagger)
- [Ejemplos de Uso](#-ejemplos-de-uso)

---

## ✨ Características

- ✅ CRUD completo de productos
- ✅ Gestión de divisas (currencies)
- ✅ Precios de productos en múltiples divisas
- ✅ Soft deletes en productos
- ✅ Validación robusta con Form Requests
- ✅ Respuestas JSON con API Resources
- ✅ Paginación de resultados
- ✅ Documentación interactiva con Swagger UI
- ✅ Arquitectura por capas con principios SOLID
- ✅ Seeders con datos de prueba

---

## 🏗️ Principios SOLID Aplicados

| Principio | Descripción | Implementación |
|-----------|-------------|----------------|
| **S** - Single Responsibility | Cada clase tiene una única responsabilidad | Controllers (presentación), Services (lógica de negocio), Repositories (acceso a datos) |
| **O** - Open/Closed | Abierto para extensión, cerrado para modificación | Interfaces permiten nuevas implementaciones sin tocar el código existente |
| **L** - Liskov Substitution | Las implementaciones son intercambiables | `ProductRepository` implementa completamente `ProductRepositoryInterface` |
| **I** - Interface Segregation | Interfaces específicas y cohesivas | `ProductRepositoryInterface` y `ProductPriceRepositoryInterface` separadas |
| **D** - Dependency Inversion | Depender de abstracciones, no de implementaciones | Controllers → Services → Interfaces (no clases concretas) |

---

## 📁 Arquitectura del Proyecto

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Controller.php              ← Controlador base con spec OpenAPI
│   │   └── Api/
│   │       ├── ProductController.php   ← CRUD de productos
│   │       └── ProductPriceController.php ← Precios multi-divisa
│   ├── Requests/
│   │   ├── StoreProductRequest.php     ← Validación al crear
│   │   ├── UpdateProductRequest.php    ← Validación al actualizar
│   │   └── StoreProductPriceRequest.php ← Validación de precios
│   └── Resources/
│       ├── ProductResource.php         ← Transformador de producto
│       ├── ProductPriceResource.php    ← Transformador de precio
│       └── CurrencyResource.php        ← Transformador de divisa
├── Models/
│   ├── Product.php                     ← Modelo con SoftDeletes
│   ├── Currency.php                    ← Modelo de divisa
│   └── ProductPrice.php                ← Modelo de precios
├── Repositories/
│   ├── Interfaces/
│   │   ├── ProductRepositoryInterface.php
│   │   └── ProductPriceRepositoryInterface.php
│   ├── ProductRepository.php           ← Implementación concreta
│   └── ProductPriceRepository.php      ← Implementación concreta
├── Services/
│   ├── ProductService.php              ← Lógica de negocio de productos
│   └── ProductPriceService.php         ← Lógica de negocio de precios
├── OpenApi/
│   └── OpenApiSpec.php                 ← Definición global de la API
└── Providers/
    └── RepositoryServiceProvider.php   ← Binding de interfaces (DIP)
```

---

## 🗃️ Modelo de Datos

### `currencies` (Divisas)
| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | integer | Identificador único |
| name | string | Código de la divisa (ej. USD, EUR) |
| symbol | string | Símbolo de la divisa (ej. $, €) |
| exchange_rate | decimal(15,6) | Tasa de cambio respecto a divisa base |

### `products` (Productos)
| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | integer | Identificador único |
| name | string | Nombre del producto |
| description | text | Descripción del producto |
| price | decimal(15,2) | Precio en la divisa base |
| currency_id | integer | FK → currencies.id |
| tax_cost | decimal(15,2) | Costo de impuestos |
| manufacturing_cost | decimal(15,2) | Costo de fabricación |
| deleted_at | timestamp | Soft delete |

### `product_prices` (Precios por Divisa)
| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | integer | Identificador único |
| product_id | integer | FK → products.id |
| currency_id | integer | FK → currencies.id |
| price | decimal(15,2) | Precio en la divisa especificada |

> **Restricción:** `(product_id, currency_id)` es único — cada producto tiene un precio por divisa.

---

## ⚙️ Requisitos

- PHP >= 8.2
- Composer >= 2.x
- SQLite (por defecto) o MySQL/PostgreSQL

---

## 🚀 Instalación

```bash
# 1. Clonar el repositorio
git clone <url-del-repositorio>
cd api-gestion-productos

# 2. Instalar dependencias
composer install

# 3. Configurar variables de entorno
cp .env.example .env
php artisan key:generate

# 4. Crear base de datos (SQLite por defecto)
touch database/database.sqlite

# 5. Ejecutar migraciones
php artisan migrate

# 6. Poblar con datos de prueba
php artisan db:seed

# 7. Generar documentación Swagger
php artisan l5-swagger:generate

# 8. Iniciar servidor de desarrollo
php artisan serve --port=8000
```

La API estará disponible en: `http://localhost:8000/api`

---

## 📡 Endpoints

### Divisas

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| `GET` | `/api/currencies` | Listar catálogo de divisas disponibles y tasas de cambio |

### Productos

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| `GET` | `/api/products` | Listar productos (paginado) |
| `POST` | `/api/products` | Crear un nuevo producto |
| `GET` | `/api/products/{id}` | Obtener un producto por ID |
| `PUT` | `/api/products/{id}` | Actualizar un producto |
| `DELETE` | `/api/products/{id}` | Eliminar un producto (soft delete) |

### Precios de Productos

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| `GET` | `/api/products/{id}/prices` | Listar precios del producto en distintas divisas |
| `POST` | `/api/products/{id}/prices` | Crear o actualizar precio en una divisa |

---

## 📖 Documentación Swagger

La documentación interactiva está disponible en:

**`http://localhost:8000/api/documentation`**

Para regenerar la documentación:

```bash
php artisan l5-swagger:generate
```

---

## 💡 Ejemplos de Uso

### Crear un producto

```bash
curl -X POST http://localhost:8000/api/products \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Laptop Pro X",
    "description": "Laptop de alto rendimiento",
    "price": 999.99,
    "currency_id": 1,
    "tax_cost": 150.00,
    "manufacturing_cost": 300.00
  }'
```

**Respuesta (201):**
```json
{
  "message": "Producto creado exitosamente.",
  "data": {
    "id": 1,
    "name": "Laptop Pro X",
    "description": "Laptop de alto rendimiento",
    "price": 999.99,
    "tax_cost": 150.00,
    "manufacturing_cost": 300.00,
    "currency": {
      "id": 1,
      "name": "USD",
      "symbol": "$",
      "exchange_rate": 1.0
    },
    "prices": [],
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
  }
}
```

### Listar productos (paginado)

```bash
curl http://localhost:8000/api/products?per_page=10
```

### Obtener producto por ID

```bash
curl http://localhost:8000/api/products/1
```

### Actualizar producto

```bash
curl -X PUT http://localhost:8000/api/products/1 \
  -H "Content-Type: application/json" \
  -d '{
    "price": 1099.99,
    "tax_cost": 165.00
  }'
```

### Eliminar producto

```bash
curl -X DELETE http://localhost:8000/api/products/1
```

### Registrar precio en otra divisa

```bash
curl -X POST http://localhost:8000/api/products/1/prices \
  -H "Content-Type: application/json" \
  -d '{
    "currency_id": 2,
    "price": 921.00
  }'
```

### Obtener precios del producto

```bash
curl http://localhost:8000/api/products/1/prices
```

**Respuesta (200):**
```json
{
  "data": [
    {
      "id": 1,
      "product_id": 1,
      "price": 921.00,
      "currency": {
        "id": 2,
        "name": "EUR",
        "symbol": "€",
        "exchange_rate": 0.921
      }
    }
  ]
}
```

### Respuesta de error de validación (422)

```json
{
  "message": "Los datos proporcionados no son válidos.",
  "errors": {
    "price": ["El precio es obligatorio."],
    "currency_id": ["La divisa seleccionada no existe."]
  }
}
```

---

## 🔒 Seguridad

- Validación estricta de entrada con Form Requests en todas las operaciones
- Soft deletes para conservar integridad referencial
- Restricciones de clave foránea a nivel de base de datos
- Restricción de unicidad `(product_id, currency_id)` para evitar duplicados
- Las respuestas de error son siempre JSON en rutas `/api/*`
- Preparado para autenticación Bearer JWT (esquema definido en Swagger)

---

## 🧪 Datos de Prueba (Seeders)

Los seeders incluyen:

**Divisas:** USD, EUR, GBP, MXN, COP, BRL

**Productos de muestra:**
- Laptop Pro X1 (USD 1,299.99)
- Monitor UltraWide 34" (USD 549.99)
- Teclado Mecánico RGB (USD 89.99)

Cada producto tiene precios en EUR y MXN pre-cargados.

---

## 🛠️ Tecnologías

| Tecnología | Versión | Uso |
|------------|---------|-----|
| Laravel | 12.x | Framework principal |
| PHP | 8.2+ | Lenguaje de programación |
| SQLite/MySQL | - | Base de datos |
| L5-Swagger | 11.x | Documentación OpenAPI 3.0 |
| swagger-php | 6.x | Generación de spec Swagger |
| Eloquent ORM | - | Acceso a datos |
