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
        // Drop old trade triggers (if they exist)
        DB::unprepared("DROP TRIGGER IF EXISTS trg_trade_create_client_calculation ON trades");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_trade_update_client_calculation ON trades");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_trade_delete_client_calculation ON trades");

        // Drop old invoice triggers (if they exist)
        DB::unprepared("DROP TRIGGER IF EXISTS trg_invoice_create_supplier_calculation ON invoices");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_invoice_update_supplier_calculation ON invoices");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_invoice_delete_supplier_calculation ON invoices");

        // Also try other possible trigger names
        DB::unprepared("DROP TRIGGER IF EXISTS trigger_calculate_client_debt_when_trade_create ON trades");
        DB::unprepared("DROP TRIGGER IF EXISTS trigger_calculate_client_debt_when_trade_update ON trades");
        DB::unprepared("DROP TRIGGER IF EXISTS trigger_calculate_client_debt_when_trade_delete ON trades");

        DB::unprepared("DROP TRIGGER IF EXISTS trigger_calculate_supplier_debt_when_invoice_create ON invoices");
        DB::unprepared("DROP TRIGGER IF EXISTS trigger_calculate_supplier_debt_when_invoice_update ON invoices");
        DB::unprepared("DROP TRIGGER IF EXISTS trigger_calculate_supplier_debt_when_invoice_delete ON invoices");

        // Drop old functions (if they exist)
        DB::unprepared("DROP FUNCTION IF EXISTS calculate_client_debt_when_trade_create()");
        DB::unprepared("DROP FUNCTION IF EXISTS calculate_client_debt_when_trade_update()");
        DB::unprepared("DROP FUNCTION IF EXISTS calculate_client_debt_when_trade_delete()");

        DB::unprepared("DROP FUNCTION IF EXISTS calculate_supplier_debt_when_invoice_create()");
        DB::unprepared("DROP FUNCTION IF EXISTS calculate_supplier_debt_when_invoice_update()");
        DB::unprepared("DROP FUNCTION IF EXISTS calculate_supplier_debt_when_invoice_delete()");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is for cleanup only, no rollback needed
        // The old triggers and functions should not be recreated
    }
};
