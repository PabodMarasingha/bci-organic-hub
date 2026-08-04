<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'delivery_status')) {
                $table->string('delivery_status')->default('ready_for_pickup')->after('status');
            }
            if (!Schema::hasColumn('orders', 'driver_id')) {
                $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('set null')->after('delivery_status');
            }
            if (!Schema::hasColumn('orders', 'delivery_fee')) {
                $table->decimal('delivery_fee', 8, 2)->default(350.00)->after('driver_id');
            }
            if (!Schema::hasColumn('orders', 'customer_name')) {
                $table->string('customer_name')->nullable();
            }
            if (!Schema::hasColumn('orders', 'customer_phone')) {
                $table->string('customer_phone')->nullable();
            }
            if (!Schema::hasColumn('orders', 'delivery_address')) {
                $table->text('delivery_address')->nullable();
            }
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->default('COD');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(array_filter([
                Schema::hasColumn('orders', 'delivery_status') ? 'delivery_status' : null,
                Schema::hasColumn('orders', 'driver_id') ? 'driver_id' : null,
                Schema::hasColumn('orders', 'delivery_fee') ? 'delivery_fee' : null,
                Schema::hasColumn('orders', 'customer_name') ? 'customer_name' : null,
                Schema::hasColumn('orders', 'customer_phone') ? 'customer_phone' : null,
                Schema::hasColumn('orders', 'payment_method') ? 'payment_method' : null,
            ]));
        });
    }
};