<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->string('name')->unique();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->string('sku')->nullable();
            $table->string('unit');
            $table->boolean('is_active')->default(true);
            $table->decimal('residue', 10, 2)->default(0);
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('markup', 10, 2)->default(0);
            $table->decimal('wholesale_price', 10, 2)->default(0);
            $table->jsonb('images')->nullable();
            $table->jsonb('main_image')->nullable();
            $table->string('description', 500)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index('sku');
            $table->index('is_active');
            $table->index('price');
            $table->index('wholesale_price');
            $table->index('residue');
            $table->index(['category_id', 'is_active', 'name']);
        });

        // Add check constraint for residue
        DB::statement('ALTER TABLE products ADD CONSTRAINT check_products_residue_non_negative CHECK (residue >= 0)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
