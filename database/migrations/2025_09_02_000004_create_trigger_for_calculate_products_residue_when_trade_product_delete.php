<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $sql = file_get_contents(database_path('sql/trade_products/calculate_products_residue_when_trade_product_delete.sql'));
        DB::unprepared($sql);

        DB::unprepared('
            CREATE TRIGGER trigger_calculate_products_residue_when_trade_product_delete
            AFTER DELETE ON trade_products
            FOR EACH ROW
            EXECUTE FUNCTION calculate_products_residue_when_trade_product_delete();
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_calculate_products_residue_when_trade_product_delete ON trade_products;');
        DB::unprepared('DROP FUNCTION IF EXISTS calculate_products_residue_when_trade_product_delete();');
    }
};
