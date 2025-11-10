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
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name_uz')->comment('Atribut nomi (o\'zbek)');
            $table->string('name_ru')->comment('Atribut nomi (rus)');
            $table->string('type')->default('select')->comment('Atribut turi (select, text, number...)');
            $table->boolean('is_active')->default(true)->comment('Atribut holati');
            $table->timestamps();

            $table->index('name_uz');
            $table->index('name_ru');
            $table->index('type');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};
