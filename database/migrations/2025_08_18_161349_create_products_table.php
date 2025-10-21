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
            $table->string('name')->comment('Mahsulot nomi');
            $table->text('description')->nullable()->comment('Mahsulot tavsifi');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade')->comment('Kategoriya ID');
            $table->foreignId('brand_id')->constrained('brands')->onDelete('cascade')->comment('Brend ID');
            $table->timestamps();
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
