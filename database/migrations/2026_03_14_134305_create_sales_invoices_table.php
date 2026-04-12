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
        Schema::create('sales_invoices', function (Blueprint $table) {
    $table->id();
    $table->string('inv_number')->unique(); 
    $table->date('invoice_date');
    $table->string('customer_name');
    $table->string('phno', 15)->nullable();
    $table->foreignId('product_id')->constrained('products');
    $table->integer('tray_use')->default(0); 
    $table->integer('quantity'); 
    $table->decimal('total_price', 10, 2);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_invoices');
    }
};
