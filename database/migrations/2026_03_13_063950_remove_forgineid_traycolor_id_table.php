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
        Schema::table('trays', function (Blueprint $table) {
            if (Schema::hasColumn('trays', 'traycolor_id')) {
                $table->dropColumn('traycolor_id');
            }
            if (!Schema::hasColumn('trays', 'traycolor')) {
                $table->string('traycolor')->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trays', function (Blueprint $table) {
            $table->dropColumn('traycolor');
            $table->foreignId('traycolor_id')->after('id')->constrained('traycolors');
        });
    }
};
