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
    // First check table exists
    if (Schema::hasTable('products')) {

        Schema::table('products', function (Blueprint $table) {

            if (!Schema::hasColumn('products', 'eggprice')) {
                $table->decimal('eggprice', 10, 2)->nullable();
            }

            if (!Schema::hasColumn('products', 'totaleggs')) {
                $table->integer('totaleggs')->nullable();
            }

            if (!Schema::hasColumn('products', 'purchase_price')) {
                $table->decimal('purchase_price', 10, 2)->nullable();
            }

            if (!Schema::hasColumn('products', 'sale_price')) {
                $table->decimal('sale_price', 10, 2)->nullable();
            }

        });

    }
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
