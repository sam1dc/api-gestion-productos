<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Servicio de productos.
 * Responsabilidad única: orquestar la lógica de negocio relacionada con productos.
 * Depende de abstracciones (Interface), no de implementaciones concretas (DIP).
 */
class ProductService
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {}

    /**
     * Obtener listado paginado de productos.
     */
    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return $this->productRepository->getAll($perPage);
    }

    /**
     * Obtener un producto por ID.
     * Lanza excepción si no existe.
     */
    public function findOrFail(int $id): Product
    {
        $product = $this->productRepository->findById($id);

        if (! $product) {
            abort(404, 'Producto no encontrado.');
        }

        return $product;
    }

    /**
     * Crear un nuevo producto.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Product
    {
        return $this->productRepository->create($data);
    }

    /**
     * Actualizar un producto existente.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Product $product, array $data): Product
    {
        return $this->productRepository->update($product, $data);
    }

    /**
     * Eliminar un producto (soft delete).
     */
    public function delete(Product $product): bool
    {
        return $this->productRepository->delete($product);
    }
}
