<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerOrder extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'subtotal'     => 'decimal:2',
        'discount'     => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'total_price'  => 'decimal:2',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Customer who placed the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Delivery zone details for the order.
     */
    public function deliveryZone()
    {
        return $this->belongsTo(DeliveryZone::class);
    }

    /**
     * Order items belonging to this order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'customer_order_id');
    }

    /**
     * Payment record associated with the order.
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'customer_order_id');
    }

    /**
     * Delivery details for the order.
     */
    public function delivery()
    {
        return $this->hasOne(Delivery::class, 'customer_order_id');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS & SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Scope query to active kitchen orders only.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeKitchenQueue(Builder $query): Builder
    {
        return $query->whereIn('status', ['pending', 'preparing', 'ready']);
    }

    /**
     * Check if the order can be cancelled by customer.
     */
    public function isCancellable(): bool
    {
        return in_array($this->status, ['pending']);
    }
}