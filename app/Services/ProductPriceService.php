<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductPrice;
use App\Repositories\Interfaces\ProductPriceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Servicio de precios de productos.
 * Responsabilidad única: orquestar la lógica de negocio de precios.
 * Depende de abstracciones (Interface), no de implementaciones concretas (DIP).
 */
class ProductPriceService
{
    public function __construct(
        private readonly ProductPriceRepositoryInterface $priceRepository
    ) {}

    /**
     * Obtener todos los precios de un producto.
     */
    public function getByProduct(Product $product): Collection
    {
        return $this->priceRepository->getByProduct($product);
    }

    /**
     * Crear o actualizar el precio de un producto en una divisa específica.
     *
     * @param  array<string, mixed>  $data
     */
    public function createOrUpdate(Product $product, array $data): ProductPrice
    {
        return $this->priceRepository->createOrUpdate($product, $data);
    }
}
