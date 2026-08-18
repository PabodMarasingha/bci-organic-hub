<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'delivery_zone_id',
        'vehicle_type',
        'vehicle_number',
        'is_available',
        'current_status',
        'current_lat',
        'current_lng',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}