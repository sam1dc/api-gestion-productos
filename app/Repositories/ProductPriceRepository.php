<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductPrice;
use App\Repositories\Interfaces\ProductPriceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Implementación concreta del repositorio de precios de productos.
 * Responsabilidad única: acceso a datos de precios de productos.
 */
class ProductPriceRepository implements ProductPriceRepositoryInterface
{
    public function __construct(private readonly ProductPrice $model) {}

    /**
     * {@inheritdoc}
     */
    public function getByProduct(Product $product): Collection
    {
        return $product->prices()->with('currency')->get();
    }

    /**
     * {@inheritdoc}
     *
     * Usa updateOrCreate para evitar duplicados (product_id + currency_id únicos).
     */
    public function createOrUpdate(Product $product, array $data): ProductPrice
    {
        $price = $product->prices()->updateOrCreate(
            ['currency_id' => $data['currency_id']],
            ['price'       => $data['price']]
        );

        return $price->load('currency');
    }
}
