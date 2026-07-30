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
        Schema::create('ingredients', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->enum('type', ['salad_base', 'topping', 'juice_base', 'fruit']);
    $table->integer('calories')->default(0);
    $table->decimal('price', 8, 2)->default(0);
    $table->boolean('in_stock')->default(true);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
