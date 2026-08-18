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
        Schema::create('product_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable(); // Seeder එකට අවශ්‍ය description column එක
            $table->enum('category', ['salad', 'juice', 'snack'])->default('salad');
            $table->decimal('price', 8, 2)->default(0.00); // Seeder එකට අවශ්‍ය price column එක
            $table->decimal('base_price', 8, 2)->nullable();
            $table->string('image')->nullable(); // Image එක සඳහා
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_items');
    }
};