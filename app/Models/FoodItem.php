<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FoodItem extends Model
{
    use HasFactory;

    protected $guarded = [];

   
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'food_item_ingredient')
                    ->withPivot('quantity_required');
    }
}