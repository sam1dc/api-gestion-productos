<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Seed de divisas base para uso en la API.
     */
    public function run(): void
    {
        $currencies = [
            ['name' => 'USD', 'symbol' => '$',  'exchange_rate' => 1.000000],
            ['name' => 'EUR', 'symbol' => '€',  'exchange_rate' => 0.921000],
            ['name' => 'GBP', 'symbol' => '£',  'exchange_rate' => 0.793000],
            ['name' => 'MXN', 'symbol' => '$',  'exchange_rate' => 17.200000],
            ['name' => 'COP', 'symbol' => '$',  'exchange_rate' => 3950.000000],
            ['name' => 'BRL', 'symbol' => 'R$', 'exchange_rate' => 4.970000],
        ];

        foreach ($currencies as $currency) {
            Currency::firstOrCreate(
                ['name' => $currency['name']],
                $currency
            );
        }
    }
}
