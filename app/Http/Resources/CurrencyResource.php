<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CurrencyResource',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'USD'),
        new OA\Property(property: 'symbol', type: 'string', example: '$'),
        new OA\Property(property: 'exchange_rate', type: 'number', format: 'float', example: 1.0),
    ]
)]
class CurrencyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'symbol'        => $this->symbol,
            'exchange_rate' => (float) $this->exchange_rate,
        ];
    }
}
