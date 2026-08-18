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
        Schema::dropIfExists('ingredients');

        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // ENUM වෙනුවට String භාවිත කර ඇත
            $table->integer('calories')->default(0);
            $table->decimal('price', 8, 2)->default(0.00);
            $table->integer('quantity')->default(20); // Low stock පරීක්ෂාව සඳහා quantity එකතු කරන ලදී
            $table->boolean('is_available')->default(true);
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