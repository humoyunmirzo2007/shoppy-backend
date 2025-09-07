<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Load and execute trigger functions
        $createFunction = file_get_contents(database_path('sql/payments/calculate_payment_type_residue_when_payment_create.sql'));
        $updateFunction = file_get_contents(database_path('sql/payments/calculate_payment_type_residue_when_payment_update.sql'));
        $deleteFunction = file_get_contents(database_path('sql/payments/calculate_payment_type_residue_when_payment_delete.sql'));

        DB::unprepared($createFunction);
        DB::unprepared($updateFunction);
        DB::unprepared($deleteFunction);

        // Create triggers
        DB::unprepared('
            CREATE TRIGGER trigger_calculate_payment_type_residue_when_payment_create
            AFTER INSERT ON payments
            FOR EACH ROW
            EXECUTE FUNCTION calculate_payment_type_residue_when_payment_create();
        ');

        DB::unprepared('
            CREATE TRIGGER trigger_calculate_payment_type_residue_when_payment_update
            AFTER UPDATE ON payments
            FOR EACH ROW
            EXECUTE FUNCTION calculate_payment_type_residue_when_payment_update();
        ');

        DB::unprepared('
            CREATE TRIGGER trigger_calculate_payment_type_residue_when_payment_delete
            AFTER DELETE ON payments
            FOR EACH ROW
            EXECUTE FUNCTION calculate_payment_type_residue_when_payment_delete();
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop triggers
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_calculate_payment_type_residue_when_payment_create ON payments;');
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_calculate_payment_type_residue_when_payment_update ON payments;');
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_calculate_payment_type_residue_when_payment_delete ON payments;');

        // Drop functions
        DB::unprepared('DROP FUNCTION IF EXISTS calculate_payment_type_residue_when_payment_create();');
        DB::unprepared('DROP FUNCTION IF EXISTS calculate_payment_type_residue_when_payment_update();');
        DB::unprepared('DROP FUNCTION IF EXISTS calculate_payment_type_residue_when_payment_delete();');
    }
};
