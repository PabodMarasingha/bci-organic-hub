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
    Schema::table('customer_orders', function (Blueprint $table) {
        $table->decimal('delivery_fee', 8, 2)->default(350.00)->after('total_amount');
    });
}

public function down(): void
{
    Schema::table('customer_orders', function (Blueprint $table) {
        $table->dropColumn('delivery_fee');
    });
}
};
