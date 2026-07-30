<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('customer_order_id')->constrained()->onDelete('cascade');
    $table->string('payment_method');
    $table->string('transaction_id')->nullable();
    $table->decimal('amount', 10, 2);
    $table->enum('payment_status', ['pending', 'completed', 'failed'])->default('pending');
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};