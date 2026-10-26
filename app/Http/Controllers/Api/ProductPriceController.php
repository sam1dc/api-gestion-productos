<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductPriceRequest;
use App\Http\Resources\ProductPriceResource;
use App\Services\ProductPriceService;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ProductPriceController extends Controller
{
    public function __construct(
        private readonly ProductService      $productService,
        private readonly ProductPriceService $priceService
    ) {}

    #[OA\Get(
        path: '/api/products/{id}/prices',
        summary: 'Obtener precios de un producto en diferentes divisas',
        tags: ['Precios de Productos'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'ID del producto', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de precios del producto',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/ProductPriceResource')
                        ),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Producto no encontrado'),
        ]
    )]
    public function index(int $id): JsonResponse
    {
        $product = $this->productService->findOrFail($id);
        $prices  = $this->priceService->getByProduct($product);

        return response()->json([
            'data' => ProductPriceResource::collection($prices),
        ]);
    }

    #[OA\Post(
        path: '/api/products/{id}/prices',
        summary: 'Registrar o actualizar el precio de un producto en una divisa',
        tags: ['Precios de Productos'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'ID del producto', schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['currency_id', 'price'],
                properties: [
                    new OA\Property(property: 'currency_id', type: 'integer', example: 2, description: 'ID de la divisa para este precio (Obligatorio). Opciones: 1=USD ($), 2=EUR (€), 3=GBP (£), 4=MXN ($), 5=COP ($), 6=BRL (R$)'),
                    new OA\Property(property: 'price', type: 'number', format: 'float', example: 849.99, description: 'Precio del producto en la divisa especificada (Obligatorio, >= 0)'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Precio registrado o actualizado exitosamente',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/ProductPriceResource'),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Producto no encontrado'),
            new OA\Response(response: 422, description: 'Error de validación'),
        ]
    )]
    public function store(StoreProductPriceRequest $request, int $id): JsonResponse
    {
        $product = $this->productService->findOrFail($id);
        $price   = $this->priceService->createOrUpdate($product, $request->validated());

        return response()->json([
            'message' => 'Precio registrado o actualizado exitosamente.',
            'data'    => new ProductPriceResource($price),
        ], 201);
    }
}
