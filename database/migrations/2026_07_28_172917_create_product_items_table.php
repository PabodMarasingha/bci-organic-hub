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
            $table->text('description')->nullable(); 
            
           
            $table->string('category')->nullable(); 
            
            $table->decimal('price', 8, 2)->default(0.00); 
            $table->decimal('base_price', 8, 2)->nullable();
            
            
            $table->integer('base_calories')->nullable();
            $table->decimal('average_rating', 3, 2)->default(0.00); 
            $table->integer('reviews_count')->default(0); 
            
            $table->string('image')->nullable(); 
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