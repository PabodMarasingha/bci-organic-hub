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
        Schema::create('delivery_profiles', function (Blueprint $table) {
            $table->id();
            // Driver ගේ User ID එක (users table එකට link වේ)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Delivery Zone ID එක (ඔයා ලඟ delivery_zones table එකක් තියෙනවා නම්)
            $table->foreignId('delivery_zone_id')->nullable()->constrained()->onDelete('set null');
            
            // Driver & Vehicle Details
            $table->string('vehicle_type')->default('motorbike'); // bike, van, three_wheeler
            $table->string('vehicle_number')->nullable();
            
            // Availability & Status Tracking
            $table->boolean('is_available')->default(true); // Online / Offline
            $table->string('current_status')->default('available'); // available, busy, offline
            
            // Live Location Data (පසු කාලීනව Map එකේ පෙන්නන්න)
            $table->decimal('current_lat', 10, 7)->nullable();
            $table->decimal('current_lng', 10, 7)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_profiles');
    }
};