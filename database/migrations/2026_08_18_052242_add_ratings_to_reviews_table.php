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
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'food_rating')) {
                $table->integer('food_rating')->default(5)->after('user_id');
            }
            if (!Schema::hasColumn('reviews', 'delivery_rating')) {
                $table->integer('delivery_rating')->default(5)->after('food_rating');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('reviews', 'food_rating')) {
                $columnsToDrop[] = 'food_rating';
            }
            if (Schema::hasColumn('reviews', 'delivery_rating')) {
                $columnsToDrop[] = 'delivery_rating';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};