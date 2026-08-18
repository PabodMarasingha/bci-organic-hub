<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryZone extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'fee', 'is_active'];

    public function staff(): HasMany
    {
        return $this->hasMany(User::class, 'delivery_zone_id');
    }
}