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
        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->constrained('attributes')->onDelete('cascade')->index();
            $table->string('value_uz')->comment('Atribut qiymati (o\'zbek)');
            $table->string('value_ru')->comment('Atribut qiymati (rus)');
            $table->string('code')->nullable()->comment('Atribut qiymati kodi');
            $table->timestamps();

            $table->index('value_uz');
            $table->index('value_ru');
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_values');
    }
};
