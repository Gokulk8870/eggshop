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
        Schema::table('salesclts', function (Blueprint $table) {
            $table->string('prefix')->unique();
             $table->String('suffix')->unique();
             $table->enum('status',['active','inactive']);
            //
        });
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
