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
        Schema::create('money_operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('operation_type'); // 'input' yoki 'output'
            $table->foreignId('payment_type_id')->constrained('payment_types');
            $table->foreignId('other_payment_type_id')->nullable()->constrained('payment_types');
            $table->foreignId('other_source_id')->nullable()->constrained('other_sources');
            $table->foreignId('cost_type_id')->nullable()->constrained('cost_types');
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers');
            $table->decimal('amount', 10, 2);
            $table->string('description')->nullable();
            $table->string('type'); // PaymentTypesEnum yoki CostTypesEnum values
            $table->date('date')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'date']);
            $table->index(['operation_type', 'date']);
            $table->index('payment_type_id');
            $table->index('other_source_id');
            $table->index('cost_type_id');
            $table->index('client_id');
            $table->index('supplier_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('money_operations');
    }
};
