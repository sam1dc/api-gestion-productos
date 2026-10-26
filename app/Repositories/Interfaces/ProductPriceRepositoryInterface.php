<?php

namespace App\Repositories\Interfaces;

use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Database\Eloquent\Collection;

interface ProductPriceRepositoryInterface
{
    /**
     * Obtener todos los precios de un producto.
     */
    public function getByProduct(Product $product): Collection;

    /**
     * Crear o actualizar el precio de un producto en una divisa.
     *
     * @param  array<string, mixed>  $data
     */
    public function createOrUpdate(Product $product, array $data): ProductPrice;
}
