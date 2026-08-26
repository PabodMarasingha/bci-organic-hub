<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'quantity',
        'unit',
        'reorder_level',
        'calories',
        'price',
        'in_stock',
    ];

    
    public function isLowStock(): bool
    {
        return $this->quantity <= $this->reorder_level;
    }
}