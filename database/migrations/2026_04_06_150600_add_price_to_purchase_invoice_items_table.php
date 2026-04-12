<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
Schema::table('purchase_invoices_items', function (Blueprint $table){

            $table->decimal('purchase_price', 10, 2)->after('eggs');
            $table->decimal('total', 10, 2)->after('purchase_price');

            // optional (best practice)
            $table->decimal('per_egg_price', 10, 2)->nullable()->after('total');

        });
    }

    public function down()
    {
        Schema::table('purchase_invoices_items', function (Blueprint $table){

            $table->dropColumn(['purchase_price', 'total', 'per_egg_price']);

        });
    }
};
