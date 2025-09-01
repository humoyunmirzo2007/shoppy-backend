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
        // Create function for invoice create trigger
        DB::unprepared(file_get_contents(database_path('sql/supplier_calculations/calculate_supplier_debt_when_invoice_create.sql')));

        // Create function for invoice update trigger
        DB::unprepared(file_get_contents(database_path('sql/supplier_calculations/calculate_supplier_debt_when_invoice_update.sql')));

        // Create function for invoice delete trigger
        DB::unprepared(file_get_contents(database_path('sql/supplier_calculations/calculate_supplier_debt_when_invoice_delete.sql')));

        // Create trigger for invoice create
        DB::unprepared("
            CREATE TRIGGER trg_invoice_create_supplier_calculation
            AFTER INSERT ON invoices
            FOR EACH ROW
            EXECUTE FUNCTION calculate_supplier_debt_when_invoice_create();
        ");

        // Create trigger for invoice update
        DB::unprepared("
            CREATE TRIGGER trg_invoice_update_supplier_calculation
            AFTER UPDATE ON invoices
            FOR EACH ROW
            EXECUTE FUNCTION calculate_supplier_debt_when_invoice_update();
        ");

        // Create trigger for invoice delete
        DB::unprepared("
            CREATE TRIGGER trg_invoice_delete_supplier_calculation
            AFTER DELETE ON invoices
            FOR EACH ROW
            EXECUTE FUNCTION calculate_supplier_debt_when_invoice_delete();
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop triggers
        DB::unprepared("DROP TRIGGER IF EXISTS trg_invoice_create_supplier_calculation ON invoices");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_invoice_update_supplier_calculation ON invoices");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_invoice_delete_supplier_calculation ON invoices");

        // Drop functions
        DB::unprepared("DROP FUNCTION IF EXISTS calculate_supplier_debt_when_invoice_create()");
        DB::unprepared("DROP FUNCTION IF EXISTS calculate_supplier_debt_when_invoice_update()");
        DB::unprepared("DROP FUNCTION IF EXISTS calculate_supplier_debt_when_invoice_delete()");
    }
};
