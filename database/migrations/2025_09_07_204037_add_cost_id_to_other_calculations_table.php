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
        Schema::table('other_calculations', function (Blueprint $table) {
            $table->foreignId('cost_id')->nullable()->constrained('costs')->cascadeOnDelete()->after('payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('other_calculations', function (Blueprint $table) {
            $table->dropForeign(['cost_id']);
            $table->dropColumn('cost_id');
        });
    }
};
