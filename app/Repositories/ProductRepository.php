<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Implementación concreta del repositorio de productos.
 * Responsabilidad única: acceso a datos de productos.
 */
class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(private readonly Product $model) {}

    /**
     * {@inheritdoc}
     */
    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with(['currency', 'prices.currency'])
            ->paginate($perPage);
    }

    /**
     * {@inheritdoc}
     */
    public function findById(int $id): ?Product
    {
        return $this->model
            ->with(['currency', 'prices.currency'])
            ->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): Product
    {
        return $this->model->create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function update(Product $product, array $data): Product
    {
        $product->update($data);

        return $product->fresh(['currency', 'prices.currency']);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Product $product): bool
    {
        return (bool) $product->delete();
    }
}
