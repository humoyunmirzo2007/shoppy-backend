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
        // Create functions for supplier calculation triggers
        DB::unprepared(file_get_contents(database_path('sql/supplier_calculations/calculate_supplier_debt_when_supplier_calculation_create.sql')));
        DB::unprepared(file_get_contents(database_path('sql/supplier_calculations/calculate_supplier_debt_when_supplier_calculation_update.sql')));
        DB::unprepared(file_get_contents(database_path('sql/supplier_calculations/calculate_supplier_debt_when_supplier_calculation_delete.sql')));

        // Create triggers on supplier_calculations table
        DB::unprepared("
            CREATE TRIGGER trg_supplier_calculation_create
            AFTER INSERT ON supplier_calculations
            FOR EACH ROW
            EXECUTE FUNCTION calculate_supplier_debt_when_supplier_calculation_create();
        ");

        DB::unprepared("
            CREATE TRIGGER trg_supplier_calculation_update
            AFTER UPDATE ON supplier_calculations
            FOR EACH ROW
            EXECUTE FUNCTION calculate_supplier_debt_when_supplier_calculation_update();
        ");

        DB::unprepared("
            CREATE TRIGGER trg_supplier_calculation_delete
            BEFORE DELETE ON supplier_calculations
            FOR EACH ROW
            EXECUTE FUNCTION calculate_supplier_debt_when_supplier_calculation_delete();
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop triggers
        DB::unprepared("DROP TRIGGER IF EXISTS trg_supplier_calculation_create ON supplier_calculations");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_supplier_calculation_update ON supplier_calculations");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_supplier_calculation_delete ON supplier_calculations");

        // Drop functions
        DB::unprepared("DROP FUNCTION IF EXISTS calculate_supplier_debt_when_supplier_calculation_create()");
        DB::unprepared("DROP FUNCTION IF EXISTS calculate_supplier_debt_when_supplier_calculation_update()");
        DB::unprepared("DROP FUNCTION IF EXISTS calculate_supplier_debt_when_supplier_calculation_delete()");
    }
};
