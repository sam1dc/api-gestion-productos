<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->cascadeOnDelete()
                  ->comment('Identificador del producto');
            $table->foreignId('currency_id')
                  ->constrained('currencies')
                  ->restrictOnDelete()
                  ->comment('Identificador de la divisa');
            $table->decimal('price', 15, 2)->comment('Precio del producto en la divisa especificada');
            $table->timestamps();

            $table->unique(['product_id', 'currency_id'], 'uq_product_currency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_prices');
    }
};
