<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'items' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that placed the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the delivery information for the order.
     */
    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }

    /**
     * Get the delivery zone for the order.
     */
    public function deliveryZone()
    {
        return $this->belongsTo(DeliveryZone::class, 'delivery_zone_id');
    }

    /**
     * Get the review associated with the order.
     */
    public function review()
    {
        return $this->hasOne(OrderReview::class);
    }
}