<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE product_items MODIFY category ENUM('salad', 'juice', 'bowl', 'wrap', 'smoothie', 'snack') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE product_items MODIFY category ENUM('salad', 'juice', 'snack') NOT NULL");
    }
};