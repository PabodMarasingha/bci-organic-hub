<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        
        if (Schema::hasTable('orders') && !Schema::hasColumn('orders', 'is_cash_collected')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->boolean('is_cash_collected')->default(false);
            });
        }

        
        $driverTable = Schema::hasTable('delivery_drivers') ? 'delivery_drivers' : (Schema::hasTable('drivers') ? 'drivers' : null);

        if ($driverTable) {
            Schema::table($driverTable, function (Blueprint $table) use ($driverTable) {
                if (!Schema::hasColumn($driverTable, 'rating')) {
                    $table->decimal('rating', 3, 2)->default(5.00);
                }
                if (!Schema::hasColumn($driverTable, 'total_ratings')) {
                    $table->integer('total_ratings')->default(0);
                }
                if (!Schema::hasColumn($driverTable, 'points')) {
                    $table->integer('points')->default(0);
                }
            });
        } else {
            Schema::create('delivery_drivers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('phone')->nullable();
                $table->string('vehicle_number')->nullable();
                $table->decimal('rating', 3, 2)->default(5.00);
                $table->integer('total_ratings')->default(0);
                $table->integer('points')->default(0);
                $table->timestamps();
            });
            $driverTable = 'delivery_drivers';
        }

        
        if (!Schema::hasTable('delivery_feedbacks')) {
            Schema::create('delivery_feedbacks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->onDelete('cascade');
                $table->unsignedBigInteger('driver_id')->nullable();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->tinyInteger('rating'); // 1 to 5 Stars
                $table->text('comment')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_feedbacks');
    }
};