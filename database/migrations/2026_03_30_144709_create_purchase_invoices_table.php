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
        Schema::create('purchase_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('inv_number');
            $table->string('suppier_name');
            $table->date('invoice_date');
            $table->string('phno',15);
            $table->decimal('total_price',10,2);
            $table->enum('payment_method',['UPI','CASH']);
            $table->unsignedBigInteger('tray_id')->nullable();
            $table->string('tray_need')->default('no');
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_invoices');
    }
};
