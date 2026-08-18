<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->enum('delivery_status', ['unassigned', 'assigned', 'out_for_delivery', 'delivered', 'cancelled'])
                  ->default('unassigned')
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->string('delivery_status')->change();
        });
    }
};