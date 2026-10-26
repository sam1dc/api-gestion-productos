<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\CurrencySeeder::class);
    }

    public function test_can_list_products(): void
    {
        Product::factory()->create([
            'name'        => 'Test Laptop',
            'price'       => 1000,
            'currency_id' => 1,
        ]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'price', 'currency', 'prices'],
                ],
                'links',
                'meta',
            ]);
    }

    public function test_can_create_product(): void
    {
        $payload = [
            'name'               => 'Nuevo Producto Test',
            'description'        => 'Descripcion de prueba',
            'price'              => 450.50,
            'currency_id'        => 1,
            'tax_cost'           => 45.00,
            'manufacturing_cost' => 150.00,
        ];

        $response = $this->postJson('/api/products', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Nuevo Producto Test')
            ->assertJsonPath('data.currency.name', 'USD');

        $this->assertDatabaseHas('products', [
            'name' => 'Nuevo Producto Test',
        ]);
    }

    public function test_validation_fails_when_currency_id_is_missing(): void
    {
        $response = $this->postJson('/api/products', [
            'name'  => 'Producto Invalido',
            'price' => 100,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['currency_id']);
    }

    public function test_can_show_product(): void
    {
        $product = Product::factory()->create(['currency_id' => 1]);

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $product->id);
    }

    public function test_can_update_product(): void
    {
        $product = Product::factory()->create(['currency_id' => 1]);

        $response = $this->putJson("/api/products/{$product->id}", [
            'name'  => 'Nombre Actualizado',
            'price' => 799.99,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Nombre Actualizado');
    }

    public function test_can_soft_delete_product(): void
    {
        $product = Product::factory()->create(['currency_id' => 1]);

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('products', ['id' => $product->id]);

        $this->getJson("/api/products/{$product->id}")->assertStatus(404);
    }

    public function test_can_manage_product_prices_in_different_currencies(): void
    {
        $product = Product::factory()->create(['currency_id' => 1]);

        // Registrar precio en EUR (currency_id = 2)
        $response = $this->postJson("/api/products/{$product->id}/prices", [
            'currency_id' => 2,
            'price'       => 920.00,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.currency.name', 'EUR');

        $this->assertEquals(920.00, (float) $response->json('data.price'));

        // Obtener lista de precios
        $listResponse = $this->getJson("/api/products/{$product->id}/prices");
        $listResponse->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_can_list_currencies(): void
    {
        $response = $this->getJson('/api/currencies');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'symbol', 'exchange_rate'],
                ],
            ]);
    }
}
