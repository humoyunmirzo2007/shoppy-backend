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
        DB::unprepared(file_get_contents(database_path('sql/invoice_products/calculate_products_residue_when_invoice_product_update.sql')));

        DB::unprepared("
            CREATE TRIGGER trg_invoice_product_update
            AFTER UPDATE ON invoice_products
            FOR EACH ROW
            EXECUTE FUNCTION calculate_products_residue_when_invoice_product_update();
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_invoice_product_update ON invoice_products");
        DB::unprepared("DROP FUNCTION IF EXISTS calculate_products_residue_when_invoice_product_update()");
    }
};
