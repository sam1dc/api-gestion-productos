<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'API Gestión de Productos',
    description: "API RESTful para la gestión de productos con soporte para múltiples divisas. Desarrollada con Laravel 12 bajo los principios SOLID.\n\n### Divisas Disponibles en el Sistema\nAl registrar productos o precios adicionales, el campo `currency_id` hace referencia a la tabla de divisas:\n* **1** - `USD` ($ - Dólar Estadounidense, tasa base 1.0)\n* **2** - `EUR` (€ - Euro, tasa 0.921)\n* **3** - `GBP` (£ - Libra Esterlina, tasa 0.793)\n* **4** - `MXN` ($ - Peso Mexicano, tasa 17.2)\n* **5** - `COP` ($ - Peso Colombiano, tasa 3950.0)\n* **6** - `BRL` (R$ - Real Brasileño, tasa 4.97)\n\n### Modelo de Producto\n* `name`: Nombre del producto (requerido, máx. 255 car.)\n* `description`: Descripción detallada (opcional)\n* `price`: Precio en la divisa base indicada por `currency_id` (requerido, numérico >= 0)\n* `currency_id`: ID de la divisa base (requerido, debe existir en la tabla currencies)\n* `tax_cost`: Costo de impuestos (opcional, numérico >= 0, default 0)\n* `manufacturing_cost`: Costo de fabricación (opcional, numérico >= 0, default 0)",
    contact: new OA\Contact(
        name: 'Equipo de Desarrollo',
        email: 'soporte@example.com'
    ),
    license: new OA\License(
        name: 'MIT',
        url: 'https://opensource.org/licenses/MIT'
    )
)]
#[OA\Server(
    url: L5_SWAGGER_CONST_HOST,
    description: 'Servidor local de desarrollo'
)]
class OpenApiSpec
{
    // Este archivo solo define la especificación OpenAPI global.
    // Los esquemas y operaciones están anotados en sus respectivos controladores y resources.
}
