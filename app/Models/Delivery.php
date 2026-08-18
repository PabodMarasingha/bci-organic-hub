<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_order_id',
        'order_id',
        'driver_id',
        'delivery_status',
        'dropoff_location',
        'dropoff_address',
        'delivered_at',
    ];

    /**
     * Main relationship to CustomerOrder.
     */
    public function customerOrder()
    {
        return $this->belongsTo(CustomerOrder::class, 'customer_order_id');
    }

    /**
     * Alias relationship for $delivery->order compatibility.
     */
    public function order()
    {
        return $this->belongsTo(CustomerOrder::class, 'customer_order_id');
    }

    /**
     * Relationship to Driver (User).
     */
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}