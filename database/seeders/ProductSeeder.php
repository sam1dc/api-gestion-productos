<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Seed de productos de muestra con precios en múltiples divisas.
     */
    public function run(): void
    {
        $usd = Currency::where('name', 'USD')->first();
        $eur = Currency::where('name', 'EUR')->first();
        $mxn = Currency::where('name', 'MXN')->first();

        if (! $usd) {
            return;
        }

        $products = [
            [
                'name'               => 'Laptop Pro X1',
                'description'        => 'Laptop de alto rendimiento con procesador Intel Core i9, 32GB RAM y SSD de 1TB.',
                'price'              => 1299.99,
                'currency_id'        => $usd->id,
                'tax_cost'           => 195.00,
                'manufacturing_cost' => 650.00,
            ],
            [
                'name'               => 'Monitor UltraWide 34"',
                'description'        => 'Monitor curvo 34 pulgadas, resolución 3440x1440, frecuencia de 144Hz.',
                'price'              => 549.99,
                'currency_id'        => $usd->id,
                'tax_cost'           => 82.50,
                'manufacturing_cost' => 220.00,
            ],
            [
                'name'               => 'Teclado Mecánico RGB',
                'description'        => 'Teclado mecánico con switches Cherry MX Red, retroiluminación RGB personalizable.',
                'price'              => 89.99,
                'currency_id'        => $usd->id,
                'tax_cost'           => 13.50,
                'manufacturing_cost' => 35.00,
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::firstOrCreate(
                ['name' => $productData['name']],
                $productData
            );

            // Agregar precio en EUR
            if ($eur) {
                ProductPrice::firstOrCreate(
                    ['product_id' => $product->id, 'currency_id' => $eur->id],
                    ['price'      => round($product->price * 0.921, 2)]
                );
            }

            // Agregar precio en MXN
            if ($mxn) {
                ProductPrice::firstOrCreate(
                    ['product_id' => $product->id, 'currency_id' => $mxn->id],
                    ['price'      => round($product->price * 17.2, 2)]
                );
            }
        }
    }
}
