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
        DB::unprepared(file_get_contents(database_path('sql/invoice_products/calculate_products_residue_when_invoice_product_create.sql')));

        DB::unprepared("
            CREATE TRIGGER trg_invoice_product_create
            AFTER INSERT ON invoice_products
            FOR EACH ROW
            EXECUTE FUNCTION calculate_products_residue_when_invoice_product_create();
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_invoice_product_create ON invoice_products");
        DB::unprepared("DROP FUNCTION IF EXISTS calculate_products_residue_when_invoice_product_create()");
    }
};
