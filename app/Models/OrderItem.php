<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'customizations' => 'array',
    ];

    /**
     * Get the order that owns the item.
     */
    public function order()
    {
        return $this->belongsTo(CustomerOrder::class, 'customer_order_id');
    }

    /**
     * Get the product associated with this order item.
     */
    public function product()
    {
        return $this->belongsTo(ProductItem::class, 'product_id');
    }
}