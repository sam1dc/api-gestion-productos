<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductPriceResource',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'product_id', type: 'integer', example: 1),
        new OA\Property(property: 'price', type: 'number', format: 'float', example: 19.99),
        new OA\Property(property: 'currency', ref: '#/components/schemas/CurrencyResource'),
    ]
)]
class ProductPriceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'product_id' => $this->product_id,
            'price'      => (float) $this->price,
            'currency'   => new CurrencyResource($this->whenLoaded('currency')),
        ];
    }
}
