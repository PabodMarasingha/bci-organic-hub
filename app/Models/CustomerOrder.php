<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CustomerOrder extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'subtotal'     => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    /* =========================================================================
       HELPER METHODS
       ========================================================================= */

    /**
     * Check if the order can be cancelled by the customer.
     */
    public function canBeCancelled(): bool
    {
        return strtolower($this->status) === 'pending';
    }

    /**
     * Check if the order is already cancelled.
     */
    public function isCancelled(): bool
    {
        return strtolower($this->status) === 'cancelled';
    }

    /**
     * Check if the order is completed.
     */
    public function isCompleted(): bool
    {
        return strtolower($this->status) === 'completed' || strtolower($this->status) === 'delivered';
    }

    /**
     * Check if the order has been reviewed by the customer.
     */
    public function hasReview(): bool
    {
        return $this->review()->exists();
    }

    /* =========================================================================
       RELATIONSHIPS
       ========================================================================= */

    /**
     * Get the user that placed the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the assigned driver for the order.
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    /**
     * Get the delivery zone associated with the order.
     */
    public function deliveryZone(): BelongsTo
    {
        return $this->belongsTo(DeliveryZone::class);
    }

    /**
     * Get all items associated with the order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'customer_order_id');
    }

    /**
     * Get the payment record for the order.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'customer_order_id');
    }

    /**
     * Get the delivery details for the order.
     */
    public function delivery(): HasOne
    {
        return $this->hasOne(Delivery::class, 'customer_order_id');
    }

    /**
     * Get the review associated with the customer order.
     */
    public function review(): HasOne
    {
        return $this->hasOne(Review::class, 'customer_order_id');
    }
}