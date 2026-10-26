<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {}

    #[OA\Get(
        path: '/api/products',
        summary: 'Listar todos los productos',
        tags: ['Productos'],
        parameters: [
            new OA\Parameter(
                name: 'per_page',
                in: 'query',
                required: false,
                description: 'Número de resultados por página',
                schema: new OA\Schema(type: 'integer', default: 15)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista paginada de productos',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/ProductResource')),
                        new OA\Property(property: 'links', type: 'object'),
                        new OA\Property(property: 'meta', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage  = (int) $request->query('per_page', 15);
        $products = $this->productService->getAll($perPage);

        return ProductResource::collection($products);
    }

    #[OA\Post(
        path: '/api/products',
        summary: 'Crear un nuevo producto',
        tags: ['Productos'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'price', 'currency_id'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', description: 'Nombre descriptivo del producto (Obligatorio, máx. 255 car.)', example: 'Laptop Pro X2'),
                    new OA\Property(property: 'description', type: 'string', description: 'Descripción detallada de especificaciones y características (Opcional)', example: 'Laptop de alto rendimiento con 32GB RAM y 1TB SSD'),
                    new OA\Property(property: 'price', type: 'number', format: 'float', description: 'Precio de venta en la divisa base indicada por currency_id (Obligatorio, >= 0)', example: 999.99),
                    new OA\Property(property: 'currency_id', type: 'integer', description: 'ID de la divisa base (Obligatorio, debe existir en la tabla currencies). Opciones disponibles: 1=USD ($), 2=EUR (€), 3=GBP (£), 4=MXN ($), 5=COP ($), 6=BRL (R$)', example: 1),
                    new OA\Property(property: 'tax_cost', type: 'number', format: 'float', description: 'Costo de impuestos aplicable al producto en la divisa base (Opcional, >= 0, default 0)', example: 150.00),
                    new OA\Property(property: 'manufacturing_cost', type: 'number', format: 'float', description: 'Costo directo de fabricación del producto en la divisa base (Opcional, >= 0, default 0)', example: 300.00),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Producto creado exitosamente',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Producto creado exitosamente.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/ProductResource'),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->create($request->validated());
        $product->load(['currency', 'prices.currency']);

        return response()->json([
            'message' => 'Producto creado exitosamente.',
            'data'    => new ProductResource($product),
        ], 201);
    }

    #[OA\Get(
        path: '/api/products/{id}',
        summary: 'Obtener un producto por ID',
        tags: ['Productos'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'ID del producto', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Datos del producto',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/ProductResource'),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Producto no encontrado'),
        ]
    )]
    public function show(int $id): JsonResponse
    {
        $product = $this->productService->findOrFail($id);

        return response()->json([
            'data' => new ProductResource($product),
        ]);
    }

    #[OA\Put(
        path: '/api/products/{id}',
        summary: 'Actualizar un producto',
        tags: ['Productos'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'ID del producto', schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', description: 'Nombre del producto', example: 'Laptop Pro X2 v2'),
                    new OA\Property(property: 'description', type: 'string', description: 'Descripción detallada', example: 'Laptop de alto rendimiento actualizada con 64GB RAM'),
                    new OA\Property(property: 'price', type: 'number', format: 'float', description: 'Precio en la divisa base (>= 0)', example: 1099.99),
                    new OA\Property(property: 'currency_id', type: 'integer', description: 'ID de la divisa base (1=USD, 2=EUR, 3=GBP, 4=MXN, 5=COP, 6=BRL)', example: 1),
                    new OA\Property(property: 'tax_cost', type: 'number', format: 'float', description: 'Costo de impuestos en divisa base (>= 0)', example: 165.00),
                    new OA\Property(property: 'manufacturing_cost', type: 'number', format: 'float', description: 'Costo de fabricación en divisa base (>= 0)', example: 320.00),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Producto actualizado exitosamente',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/ProductResource'),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Producto no encontrado'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        $product = $this->productService->findOrFail($id);
        $updated = $this->productService->update($product, $request->validated());

        return response()->json([
            'message' => 'Producto actualizado exitosamente.',
            'data'    => new ProductResource($updated),
        ]);
    }

    #[OA\Delete(
        path: '/api/products/{id}',
        summary: 'Eliminar un producto (soft delete)',
        tags: ['Productos'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'ID del producto', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Producto eliminado exitosamente',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Producto eliminado exitosamente.'),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Producto no encontrado'),
        ]
    )]
    public function destroy(int $id): JsonResponse
    {
        $product = $this->productService->findOrFail($id);
        $this->productService->delete($product);

        return response()->json([
            'message' => 'Producto eliminado exitosamente.',
        ]);
    }
}
