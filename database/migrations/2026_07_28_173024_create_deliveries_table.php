<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
    $table->id();
    $table->foreignId('customer_order_id')->constrained()->onDelete('cascade');
    $table->foreignId('driver_id')->nullable()->constrained('users');
    $table->string('dropoff_location')->nullable();
    $table->enum('delivery_status', ['unassigned', 'picked_up', 'delivered'])->default('unassigned');
    $table->timestamp('delivered_at')->nullable();
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};