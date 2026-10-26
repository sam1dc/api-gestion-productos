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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->comment('Nombre del producto');
            $table->text('description')->nullable()->comment('Descripción del producto');
            $table->decimal('price', 15, 2)->comment('Precio del producto en la divisa base');
            $table->foreignId('currency_id')
                  ->constrained('currencies')
                  ->restrictOnDelete()
                  ->comment('Identificador de la divisa base');
            $table->decimal('tax_cost', 15, 2)->default(0.00)->comment('Costo de impuestos del producto');
            $table->decimal('manufacturing_cost', 15, 2)->default(0.00)->comment('Costo de fabricación del producto');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
