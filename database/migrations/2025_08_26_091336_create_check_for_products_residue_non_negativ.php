<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            ALTER TABLE products
            ADD CONSTRAINT chk_products_residue_non_negative
            CHECK (residue >= 0);
        ");
    }

    public function down(): void
    {
        DB::unprepared("
            ALTER TABLE products
            DROP CONSTRAINT IF EXISTS chk_products_residue_non_negative;
        ");
    }
};
