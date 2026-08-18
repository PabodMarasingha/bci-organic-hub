<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ingredient_product_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('ingredient_id')->constrained()->onDelete('cascade');
            
            // Meals Customize කිරීමට අදාළ Extra Data
            $table->boolean('is_default')->default(true); // Base meal එකේ මුලින්ම තියෙන එකක්ද?
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingredient_product_item');
    }
};