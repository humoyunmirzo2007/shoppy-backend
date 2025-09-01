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
        // Create function for trade create trigger
        DB::unprepared(file_get_contents(database_path('sql/client_calculations/calculate_client_debt_when_trade_create.sql')));

        // Create function for trade update trigger
        DB::unprepared(file_get_contents(database_path('sql/client_calculations/calculate_client_debt_when_trade_update.sql')));

        // Create function for trade delete trigger
        DB::unprepared(file_get_contents(database_path('sql/client_calculations/calculate_client_debt_when_trade_delete.sql')));

        // Create trigger for trade create
        DB::unprepared("
            CREATE TRIGGER trg_trade_create_client_calculation
            AFTER INSERT ON trades
            FOR EACH ROW
            EXECUTE FUNCTION calculate_client_debt_when_trade_create();
        ");

        // Create trigger for trade update
        DB::unprepared("
            CREATE TRIGGER trg_trade_update_client_calculation
            AFTER UPDATE ON trades
            FOR EACH ROW
            EXECUTE FUNCTION calculate_client_debt_when_trade_update();
        ");

        // Create trigger for trade delete
        DB::unprepared("
            CREATE TRIGGER trg_trade_delete_client_calculation
            AFTER DELETE ON trades
            FOR EACH ROW
            EXECUTE FUNCTION calculate_client_debt_when_trade_delete();
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop triggers
        DB::unprepared("DROP TRIGGER IF EXISTS trg_trade_create_client_calculation ON trades");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_trade_update_client_calculation ON trades");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_trade_delete_client_calculation ON trades");

        // Drop functions
        DB::unprepared("DROP FUNCTION IF EXISTS calculate_client_debt_when_trade_create()");
        DB::unprepared("DROP FUNCTION IF EXISTS calculate_client_debt_when_trade_update()");
        DB::unprepared("DROP FUNCTION IF EXISTS calculate_client_debt_when_trade_delete()");
    }
};
