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
        if (!Schema::hasTable('ingredients')) {
            Schema::create('ingredients', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('type'); // Salad Base, Protein, Topping, Fruit, Juice Base, Dressing
                $table->integer('calories')->default(0);
                $table->decimal('price', 8, 2)->default(0.00);
                $table->boolean('is_available')->default(true);
                $table->timestamps();
            });
        } else {
            Schema::table('ingredients', function (Blueprint $table) {
                if (!Schema::hasColumn('ingredients', 'type')) {
                    $table->string('type')->after('name');
                }
                if (!Schema::hasColumn('ingredients', 'calories')) {
                    $table->integer('calories')->default(0)->after('type');
                }
                if (!Schema::hasColumn('ingredients', 'price')) {
                    $table->decimal('price', 8, 2)->default(0.00)->after('calories');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};