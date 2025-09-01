<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $sql = file_get_contents(database_path('sql/trade_products/calculate_products_residue_when_trade_product_create.sql'));
        DB::unprepared($sql);

        DB::unprepared('
            CREATE TRIGGER trigger_calculate_products_residue_when_trade_product_create
            AFTER INSERT ON trade_products
            FOR EACH ROW
            EXECUTE FUNCTION calculate_products_residue_when_trade_product_create();
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_calculate_products_residue_when_trade_product_create ON trade_products;');
        DB::unprepared('DROP FUNCTION IF EXISTS calculate_products_residue_when_trade_product_create();');
    }
};
