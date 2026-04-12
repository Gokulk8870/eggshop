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
        Schema::create('tray_transactions', function (Blueprint $table) {
            $table->id();
           $table->foreignId('tray_id')
                    ->constrained()
                    ->cascadeOnDelete();

            $table->foreignId('customer_id')
                    ->nullable()
                    ->constrained()
                    ->nullOnDelete();
            $table->enum('type',['out','return','damage','lost']);
            $table->integer('quantity');
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tray_transactions');
    }
};
