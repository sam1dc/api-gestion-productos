<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

class CurrencyController extends Controller
{
    #[OA\Get(
        path: '/api/currencies',
        summary: 'Listar todas las divisas disponibles',
        description: 'Retorna el catálogo de divisas registradas en el sistema con su código, símbolo y tasa de cambio respecto a la divisa base (USD).',
        tags: ['Divisas'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de divisas disponibles',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/CurrencyResource')
                        ),
                    ]
                )
            ),
        ]
    )]
    public function index(): AnonymousResourceCollection
    {
        return CurrencyResource::collection(Currency::all());
    }
}
