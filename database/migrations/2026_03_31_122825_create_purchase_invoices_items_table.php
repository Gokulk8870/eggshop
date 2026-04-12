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
        Schema::create('purchase_invoices_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')
                    ->constrained('purchase_invoices')
                    ->onDelete('cascade');

            $table->integer('tray_use')->default(0);
            $table->foreignId('product_id')
                    ->constrained('products')
                    ->onDelete('cascade');
            $table->integer('quantity');
            $table->integer('eggs')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_invoices_items');
    }
};
