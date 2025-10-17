<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Categories jadvalini yaratish
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Kategoriya nomi');
            $table->text('description')->nullable()->comment('Kategoriya tavsifi');
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade')->comment('Ota kategoriya ID');
            $table->foreignId('first_parent_id')->nullable()->constrained('categories')->onDelete('cascade')->comment('Birinchi ota kategoriya ID');
            $table->boolean('is_active')->default(true)->comment('Kategoriya faol yoki nofaol');
            $table->integer('sort_order')->default(0)->comment('Tartib raqami');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index('parent_id');
            $table->index('first_parent_id');
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    /**
     * Categories jadvalini o'chirish
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
