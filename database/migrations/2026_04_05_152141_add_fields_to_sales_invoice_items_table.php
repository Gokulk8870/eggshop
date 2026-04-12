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
        $table->decimal('purchase_price', 10, 2)->after('product_id');
        $table->decimal('sale_price', 10, 2)->after('purchase_price');
        $table->decimal('total', 10, 2)->after('sale_price');
        $table->decimal('profit', 10, 2)->after('total');
    });
}

public function down()
{
    Schema::table('sales_invoice_items', function (Blueprint $table) {
        $table->dropColumn(['purchase_price', 'sale_price', 'total', 'profit']);
    });
}
};
