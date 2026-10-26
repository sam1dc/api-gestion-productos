<?php

namespace App\Providers;

use App\Repositories\Interfaces\ProductPriceRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\ProductPriceRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Service Provider para la inyección de dependencias.
 * Aplica el principio de Inversión de Dependencias (DIP):
 * los módulos de alto nivel dependen de abstracciones (Interfaces),
 * no de implementaciones concretas (Repositories).
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class
        );

        $this->app->bind(
            ProductPriceRepositoryInterface::class,
            ProductPriceRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
