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
        // Load and execute cost trigger functions
        $createTriggerSql = file_get_contents(database_path('sql/costs/calculate_cost_effects_when_cost_create.sql'));
        $updateTriggerSql = file_get_contents(database_path('sql/costs/calculate_cost_effects_when_cost_update.sql'));
        $deleteTriggerSql = file_get_contents(database_path('sql/costs/calculate_cost_effects_when_cost_delete.sql'));

        DB::unprepared($createTriggerSql);
        DB::unprepared($updateTriggerSql);
        DB::unprepared($deleteTriggerSql);

        // Create triggers that call the functions
        DB::unprepared("
            CREATE TRIGGER trigger_calculate_cost_effects_when_cost_create
            AFTER INSERT ON costs
            FOR EACH ROW
            EXECUTE FUNCTION calculate_cost_effects_when_cost_create();
        ");

        DB::unprepared("
            CREATE TRIGGER trigger_calculate_cost_effects_when_cost_update
            AFTER UPDATE ON costs
            FOR EACH ROW
            EXECUTE FUNCTION calculate_cost_effects_when_cost_update();
        ");

        DB::unprepared("
            CREATE TRIGGER trigger_calculate_cost_effects_when_cost_delete
            AFTER DELETE ON costs
            FOR EACH ROW
            EXECUTE FUNCTION calculate_cost_effects_when_cost_delete();
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop triggers
        DB::unprepared("DROP TRIGGER IF EXISTS trigger_calculate_cost_effects_when_cost_create ON costs;");
        DB::unprepared("DROP TRIGGER IF EXISTS trigger_calculate_cost_effects_when_cost_update ON costs;");
        DB::unprepared("DROP TRIGGER IF EXISTS trigger_calculate_cost_effects_when_cost_delete ON costs;");

        // Drop functions
        DB::unprepared("DROP FUNCTION IF EXISTS calculate_cost_effects_when_cost_create();");
        DB::unprepared("DROP FUNCTION IF EXISTS calculate_cost_effects_when_cost_update();");
        DB::unprepared("DROP FUNCTION IF EXISTS calculate_cost_effects_when_cost_delete();");
    }
};
