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
        if (Schema::hasTable('product_items')) {
            Schema::table('product_items', function (Blueprint $table) {
                
                if (!Schema::hasColumn('product_items', 'base_calories')) {
                    $table->integer('base_calories')->default(250);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('product_items')) {
            Schema::table('product_items', function (Blueprint $table) {
                if (Schema::hasColumn('product_items', 'base_calories')) {
                    $table->dropColumn('base_calories');
                }
            });
        }
    }
};