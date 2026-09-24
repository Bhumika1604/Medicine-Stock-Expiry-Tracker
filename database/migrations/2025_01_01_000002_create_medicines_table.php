<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->string('name');
            $table->string('generic_name')->nullable();
            $table->string('manufacturer');
            $table->string('dosage_form', 50); // Tablet, Capsule, Syrup, etc.
            $table->string('strength', 50)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['name']);
            $table->index(['generic_name']);
            $table->index(['manufacturer']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
