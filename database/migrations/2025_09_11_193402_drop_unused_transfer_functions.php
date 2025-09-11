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
        // Drop unused transfer functions that are no longer needed
        DB::unprepared('DROP FUNCTION IF EXISTS calculate_transfer_when_transfer_create();');
        DB::unprepared('DROP FUNCTION IF EXISTS calculate_transfer_when_transfer_delete();');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is for cleanup only, no rollback needed
        // The transfer functions should not be recreated as they are no longer used
    }
};
