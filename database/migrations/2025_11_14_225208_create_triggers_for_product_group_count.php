<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create trigger functions for product_group products_count
        DB::unprepared(file_get_contents(database_path('sql/products/update_product_group_count_when_product_create.sql')));
        DB::unprepared(file_get_contents(database_path('sql/products/update_product_group_count_when_product_delete.sql')));
        DB::unprepared(file_get_contents(database_path('sql/products/update_product_group_count_when_product_update.sql')));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop triggers
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_update_product_group_count_when_product_create ON products;');
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_update_product_group_count_when_product_delete ON products;');
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_update_product_group_count_when_product_update ON products;');

        // Drop functions
        DB::unprepared('DROP FUNCTION IF EXISTS update_product_group_count_when_product_create();');
        DB::unprepared('DROP FUNCTION IF EXISTS update_product_group_count_when_product_delete();');
        DB::unprepared('DROP FUNCTION IF EXISTS update_product_group_count_when_product_update();');
    }
};
