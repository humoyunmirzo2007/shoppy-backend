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
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->cascadeOnDelete()->after('cost_id');
            $table->index(['invoice_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('other_calculations', function (Blueprint $table) {
            $table->dropForeign(['invoice_id']);
            $table->dropIndex(['invoice_id', 'type']);
            $table->dropColumn('invoice_id');
        });
    }
};
