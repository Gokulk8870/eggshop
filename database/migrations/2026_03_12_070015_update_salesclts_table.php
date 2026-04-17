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
    if (Schema::hasTable('salesclts')) {

        Schema::table('salesclts', function (Blueprint $table) {

            if (!Schema::hasColumn('salesclts', 'prefix')) {
                $table->string('prefix')->unique();
            }

            if (!Schema::hasColumn('salesclts', 'suffix')) {
                $table->string('suffix')->unique();
            }

            if (!Schema::hasColumn('salesclts', 'status')) {
                $table->enum('status', ['active', 'inactive'])->default('active');
            }

        });
    }
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salesclts', function (Blueprint $table) {
            //
        });
    }
};
