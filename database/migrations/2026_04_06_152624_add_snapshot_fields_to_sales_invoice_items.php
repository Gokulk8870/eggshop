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
        Schema::table('sales_invoice_items', function (Blueprint $table) {

            $table->string('product_name')->after('product_id');
            $table->string('size')->nullable()->after('product_name');
            $table->string('color')->nullable()->after('size');

           

        });
    }

    public function down()
    {
        Schema::table('sales_invoice_items', function (Blueprint $table) {
            $table->dropColumn(['product_name','size','color']);
        });
    }
};
