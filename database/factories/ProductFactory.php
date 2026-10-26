<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name'               => fake()->word(),
            'description'        => fake()->sentence(),
            'price'              => fake()->randomFloat(2, 10, 1000),
            'currency_id'        => Currency::query()->first()->id ?? 1,
            'tax_cost'           => fake()->randomFloat(2, 1, 50),
            'manufacturing_cost' => fake()->randomFloat(2, 5, 200),
        ];
    }
}
