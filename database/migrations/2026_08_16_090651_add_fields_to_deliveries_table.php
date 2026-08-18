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
        Schema::table('deliveries', function (Blueprint $table) {
            if (!Schema::hasColumn('deliveries', 'order_id')) {
                $table->foreignId('order_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('deliveries', 'driver_id')) {
                $table->foreignId('driver_id')->nullable()->after('order_id')->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('deliveries', 'delivery_status')) {
                $table->string('delivery_status')->default('unassigned')->after('driver_id');
            }
            if (!Schema::hasColumn('deliveries', 'dropoff_address')) {
                $table->text('dropoff_address')->nullable()->after('delivery_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            // පළමුව Foreign Key constraints ඉවත් කරන්න
            if (Schema::hasColumn('deliveries', 'order_id')) {
                $table->dropForeign(['order_id']);
            }
            if (Schema::hasColumn('deliveries', 'driver_id')) {
                $table->dropForeign(['driver_id']);
            }

            // ඉන්පසු columns ඉවත් කරන්න
            $table->dropColumn([
                'order_id', 
                'driver_id', 
                'delivery_status', 
                'dropoff_address'
            ]);
        });
    }
};