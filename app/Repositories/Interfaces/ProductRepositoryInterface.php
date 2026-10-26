<?php

namespace App\Repositories\Interfaces;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    /**
     * Obtener todos los productos paginados.
     */
    public function getAll(int $perPage = 15): LengthAwarePaginator;

    /**
     * Encontrar un producto por su ID.
     */
    public function findById(int $id): ?Product;

    /**
     * Crear un nuevo producto.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Product;

    /**
     * Actualizar un producto existente.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Product $product, array $data): Product;

    /**
     * Eliminar (soft-delete) un producto.
     */
    public function delete(Product $product): bool;
}
