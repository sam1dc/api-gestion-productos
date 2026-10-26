<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductResource',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Laptop Pro X'),
        new OA\Property(property: 'description', type: 'string', example: 'Laptop de alta gama'),
        new OA\Property(property: 'price', type: 'number', format: 'float', example: 999.99),
        new OA\Property(property: 'tax_cost', type: 'number', format: 'float', example: 150.00),
        new OA\Property(property: 'manufacturing_cost', type: 'number', format: 'float', example: 300.00),
        new OA\Property(property: 'currency', ref: '#/components/schemas/CurrencyResource'),
        new OA\Property(property: 'prices', type: 'array', items: new OA\Items(ref: '#/components/schemas/ProductPriceResource')),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'description'        => $this->description,
            'price'              => (float) $this->price,
            'tax_cost'           => (float) $this->tax_cost,
            'manufacturing_cost' => (float) $this->manufacturing_cost,
            'currency'           => new CurrencyResource($this->whenLoaded('currency')),
            'prices'             => ProductPriceResource::collection($this->whenLoaded('prices')),
            'created_at'         => $this->created_at?->toISOString(),
            'updated_at'         => $this->updated_at?->toISOString(),
        ];
    }
}
