<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create trigger for transfer creation
        $createTriggerSql = file_get_contents(database_path('sql/transfers/calculate_transfer_when_transfer_create.sql'));
        DB::unprepared($createTriggerSql);

        // Create trigger for transfer deletion
        $deleteTriggerSql = file_get_contents(database_path('sql/transfers/calculate_transfer_when_transfer_delete.sql'));
        DB::unprepared($deleteTriggerSql);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_calculate_transfer_when_transfer_create ON payments;');
        DB::unprepared('DROP FUNCTION IF EXISTS calculate_transfer_when_transfer_create();');

        DB::unprepared('DROP TRIGGER IF EXISTS trigger_calculate_transfer_when_transfer_delete ON payments;');
        DB::unprepared('DROP FUNCTION IF EXISTS calculate_transfer_when_transfer_delete();');
    }
};
