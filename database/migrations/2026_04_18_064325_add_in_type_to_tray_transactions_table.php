<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Expand ENUM to include 'in'
        DB::statement("ALTER TABLE tray_transactions MODIFY COLUMN type ENUM('in','out','return','damage','lost') NOT NULL");

        // Step 2: Fix wrongly classified purchase transactions
        // Safe: only touches rows where supplier_id IS NOT NULL (supplier deliveries)
        // Customer returns always have supplier_id = NULL, so they are untouched
        DB::statement("UPDATE tray_transactions SET type = 'in' WHERE supplier_id IS NOT NULL AND type = 'return'");
    }

    public function down(): void
    {
        // Revert the data fix before shrinking the ENUM back
        DB::statement("UPDATE tray_transactions SET type = 'return' WHERE supplier_id IS NOT NULL AND type = 'in'");

        DB::statement("ALTER TABLE tray_transactions MODIFY COLUMN type ENUM('out','return','damage','lost') NOT NULL");
    }
};
