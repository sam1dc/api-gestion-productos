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
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique()->comment('Nombre o código de la divisa (ej. USD, EUR)');
            $table->string('symbol', 10)->comment('Símbolo de la divisa (ej. $, €)');
            $table->decimal('exchange_rate', 15, 6)->default(1.000000)->comment('Tasa de cambio respecto a la divisa base');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
