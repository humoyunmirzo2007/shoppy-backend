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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade')->comment('Mahsulot ID');
            $table->string('sku')->unique()->comment('SKU kodi');
            $table->decimal('price', 10, 2)->comment('Narx');
            $table->integer('stock')->default(0)->comment('Ombor miqdori');
            $table->string('image_url')->nullable()->comment('Rasm URL');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
