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
        Schema::create('variant_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variant_id')->constrained('product_variants')->onDelete('cascade')->comment('Variant ID');
            $table->foreignId('attribute_value_id')->constrained('attribute_values')->onDelete('cascade')->comment('Atribut qiymati ID');
            $table->timestamps();

            // Bir variant va atribut qiymati kombinatsiyasi takrorlanmasligi uchun
            $table->unique(['variant_id', 'attribute_value_id'], 'variant_attribute_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variant_attributes');
    }
};
