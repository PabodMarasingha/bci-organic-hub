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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            
            
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            
            $table->foreignId('customer_order_id')->nullable()->constrained('customer_orders')->cascadeOnDelete();
            
            
            $table->foreignId('product_item_id')->nullable()->constrained('product_items')->cascadeOnDelete();
            
            // Ratings 
            $table->unsignedTinyInteger('rating')->nullable(); 
            $table->unsignedTinyInteger('delivery_rating')->nullable(); 
            
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};