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
        Schema::create('products', function (Blueprint $table) {
    $table->id();

    $table->string('product_name');

    $table->enum('size', ['small','medium','large']); 

    $table->enum('color', ['white','brown']); 

    $table->decimal('price',8,2)->default(0);

    $table->integer('quantity')->default(0); 

    $table->foreignId('tray_id')
          ->nullable()
          ->constrained('trays')
          ->nullOnDelete();

    $table->foreignId('supplier_id')
          ->nullable()
          ->constrained('suppliers')
          ->nullOnDelete();

    $table->enum('status',['active','inactive'])->default('active');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
