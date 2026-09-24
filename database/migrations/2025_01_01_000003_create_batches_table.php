<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained('medicines')->cascadeOnDelete();
            $table->string('batch_number');
            $table->date('manufacturing_date')->nullable();
            $table->date('expiry_date');
            $table->unsignedInteger('quantity')->default(0);
            $table->unsignedInteger('minimum_stock_level')->default(10);
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->decimal('selling_price', 10, 2)->nullable();
            $table->string('supplier_name')->nullable();
            $table->timestamps();

            // Batch numbers must be unique per medicine (not globally).
            $table->unique(['medicine_id', 'batch_number']);
            $table->index('expiry_date');
            $table->index('quantity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
