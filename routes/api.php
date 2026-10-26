<?php

use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductPriceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí se registran las rutas de la API RESTful para la gestión de productos.
| Todas las rutas tienen el prefijo /api automáticamente.
|
*/

// Catálogo de divisas disponibles
Route::get('currencies', [CurrencyController::class, 'index'])->name('currencies.index');

// Endpoints de Productos
Route::apiResource('products', ProductController::class)->parameters([
    'products' => 'id',
]);

// Endpoints de Precios de Productos en diferentes divisas
Route::prefix('products/{id}')->group(function () {
    Route::get('prices', [ProductPriceController::class, 'index'])->name('products.prices.index');
    Route::post('prices', [ProductPriceController::class, 'store'])->name('products.prices.store');
});
