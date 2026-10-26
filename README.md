# API RESTful - Gestión de Productos y Divisas

API RESTful desarrollada con **Laravel 12** para la gestión de productos con soporte para múltiples divisas. Diseñada bajo arquitectura limpia y principios **SOLID**.

---

## 🚀 Acceso en Vivo (Producción - Render)

La API y su documentación interactiva se encuentran **desplegadas en la nube y 100% operativas**. No es necesario instalar ni configurar nada localmente para probarla:

* 📖 **Documentación Interactiva Swagger UI:**  
  👉 **[https://api-gestion-productos-9bgj.onrender.com/api/documentation](https://api-gestion-productos-9bgj.onrender.com/api/documentation)**  
  *(Permite probar todos los endpoints en vivo con el botón "Try it out" y ver respuestas reales).*

* 🌐 **URL Base de la API:**  
  `https://api-gestion-productos-9bgj.onrender.com/api`

---

## 📂 Formatos de Documentación Disponibles

Para facilitar la revisión en cualquiera de las herramientas solicitadas, se proporcionan:

1. **Swagger / OpenAPI 3.0 (En Vivo):**  
   - Interfaz web interactiva: [https://api-gestion-productos-9bgj.onrender.com/api/documentation](https://api-gestion-productos-9bgj.onrender.com/api/documentation)  
   - Especificación OpenAPI JSON: [https://api-gestion-productos-9bgj.onrender.com/docs/api-docs.json](https://api-gestion-productos-9bgj.onrender.com/docs/api-docs.json) *(o archivo en repo: [`storage/api-docs/api-docs.json`](storage/api-docs/api-docs.json))*.

2. **Colección Postman / Insomnia (JSON):**  
   - Archivo listo para importar: [`postman_collection.json`](postman_collection.json) *(incluye todos los endpoints con payloads de prueba y variable de entorno `baseUrl` preconfigurada hacia Render)*.

3. **Documento Técnico:**  
   - Este archivo `README.md` con la arquitectura, principios SOLID y catálogo de endpoints.

---

## 📡 Catálogo de Endpoints

Todos los endpoints retornan respuestas estandarizadas en formato **JSON**.

| Método | Endpoint | Descripción | Parámetros Query / Body |
|:---:|---|---|---|
| `GET` | `/api/currencies` | Listar catálogo de divisas y tasas de cambio | - |
| `GET` | `/api/products` | Listar productos con paginación | `?page=1&per_page=15` |
| `POST` | `/api/products` | Crear un producto en su divisa base | `name`, `price`, `currency_id`, `description`, `tax_cost`, `manufacturing_cost` |
| `GET` | `/api/products/{id}` | Obtener detalle de un producto por ID | - |
| `PUT` | `/api/products/{id}` | Actualizar datos de un producto | Campos a actualizar (`name`, `price`, etc.) |
| `DELETE` | `/api/products/{id}` | Eliminar lógicamente un producto (Soft Delete) | - |
| `GET` | `/api/products/{id}/prices` | Listar precios del producto en distintas divisas | - |
| `POST` | `/api/products/{id}/prices` | Registrar o actualizar precio en una divisa | `currency_id`, `price` |

---

## 🏗️ Principios SOLID Aplicados

| Principio | Aplicación en el Proyecto |
|---|---|
| **S - Single Responsibility** | Capas estrictamente desacopladas: **FormRequests** (validación y 422), **Controllers** (HTTP), **Services** (reglas de negocio), **Repositories** (acceso a base de datos con Eloquent), **Resources** (transformación JSON). |
| **O - Open/Closed** | El sistema es extensible mediante interfaces sin modificar los controladores o servicios existentes. |
| **L - Liskov Substitution** | `ProductRepository` y `ProductPriceRepository` cumplen rigurosamente los contratos de sus interfaces, permitiendo sustituciones transparentes. |
| **I - Interface Segregation** | Interfaces divididas y cohesivas: `ProductRepositoryInterface` y `ProductPriceRepositoryInterface`. |
| **D - Dependency Inversion** | Controladores y Servicios dependen de abstracciones (interfaces inyectadas mediante `RepositoryServiceProvider`). |

---

## 🧪 Pruebas Automatizadas

El proyecto cuenta con una suite completa de pruebas funcionales automatizadas:

```bash
php artisan test
```

```text
PASS  Tests\Feature\ProductApiTest
✓ can list products
✓ can paginate products with page and per page
✓ can create product
✓ validation fails when currency id is missing
✓ can show product
✓ can update product
✓ can soft delete product
✓ can manage product prices in different currencies
✓ can list currencies
```

---

## 💻 Ejecución Local (Opcional)

Si deseas clonar y ejecutar el proyecto en tu máquina local:

```bash
# 1. Clonar e instalar dependencias
git clone https://github.com/sam1dc/api-gestion-productos.git
cd api-gestion-productos
composer install

# 2. Configurar entorno y generar APP_KEY
cp .env.example .env
php artisan key:generate

# 3. Base de datos SQLite (recomendada - sin configuración externa)
touch database/database.sqlite
php artisan migrate --seed

# 4. Iniciar servidor local
php artisan serve
```
*Swagger local: `http://localhost:8000/api/documentation`*
