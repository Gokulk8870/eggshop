<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up(): void
    {
        Schema::table('purchase_invoices', function (Blueprint $table) {
            // Rename column → correct column
            $table->renameColumn('suppier_name', 'supplier_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_invoices', function (Blueprint $table) {
            // Rollback back to old name
            $table->renameColumn('supplier_name', 'suppier_name');
        });
    }
};
