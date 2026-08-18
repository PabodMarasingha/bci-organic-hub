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
    Schema::create('food_item_ingredient', function (Blueprint $table) {
        $table->id();
        $table->foreignId('food_item_id')->constrained()->cascadeOnDelete();
        $table->foreignId('ingredient_id')->constrained()->cascadeOnDelete();
        $table->decimal('quantity_required', 10, 2); // එක් Portion එකකට අවශ්‍ය අමුද්‍රව්‍ය ප්‍රමාණය
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_item_ingredient');
    }
};
