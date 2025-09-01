<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trade_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trade_id')->constrained('trades')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('count');
            $table->decimal('price', 15, 2);
            $table->decimal('total_price', 15, 2);
            $table->date('date');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index(['trade_id', 'product_id']);
            $table->index('product_id');
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trade_products');
    }
};
