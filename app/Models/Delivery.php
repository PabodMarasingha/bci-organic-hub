<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(CustomerOrder::class, 'customer_order_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}