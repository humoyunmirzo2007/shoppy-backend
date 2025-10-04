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
        // Create functions for client calculation triggers
        DB::unprepared(file_get_contents(database_path('sql/client_calculations/calculate_client_debt_when_client_calculation_create.sql')));
        DB::unprepared(file_get_contents(database_path('sql/client_calculations/calculate_client_debt_when_client_calculation_update.sql')));
        DB::unprepared(file_get_contents(database_path('sql/client_calculations/calculate_client_debt_when_client_calculation_delete.sql')));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop triggers
        DB::unprepared("DROP TRIGGER IF EXISTS trg_client_calculation_create ON client_calculations");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_client_calculation_update ON client_calculations");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_client_calculation_delete ON client_calculations");

        // Drop functions
        DB::unprepared("DROP FUNCTION IF EXISTS calculate_client_debt_when_client_calculation_create()");
        DB::unprepared("DROP FUNCTION IF EXISTS calculate_client_debt_when_client_calculation_update()");
        DB::unprepared("DROP FUNCTION IF EXISTS calculate_client_debt_when_client_calculation_delete()");
    }
};
