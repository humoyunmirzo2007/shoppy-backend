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
        // Create trigger functions
        DB::unprepared(file_get_contents(database_path('sql/money_operations/calculate_money_operation_effects_when_create.sql')));
        DB::unprepared(file_get_contents(database_path('sql/money_operations/calculate_money_operation_effects_when_update.sql')));
        DB::unprepared(file_get_contents(database_path('sql/money_operations/calculate_money_operation_effects_when_delete.sql')));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop triggers
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_calculate_money_operation_effects_when_create ON money_operations;');
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_calculate_money_operation_effects_when_update ON money_operations;');
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_calculate_money_operation_effects_when_delete ON money_operations;');

        // Drop functions
        DB::unprepared('DROP FUNCTION IF EXISTS calculate_money_operation_effects_when_create();');
        DB::unprepared('DROP FUNCTION IF EXISTS calculate_money_operation_effects_when_update();');
        DB::unprepared('DROP FUNCTION IF EXISTS calculate_money_operation_effects_when_delete();');
    }
};
