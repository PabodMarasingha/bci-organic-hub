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

    public function order()
    {
        return $this->belongsTo(CustomerOrder::class, 'customer_order_id');
    }
}