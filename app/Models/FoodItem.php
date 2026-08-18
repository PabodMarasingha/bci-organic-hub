<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FoodItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Food item එකක් සෑදීමට අවශ්‍ය අමුද්‍රව්‍ය (Ingredients) ලබා ගැනීම
     */
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'food_item_ingredient')
                    ->withPivot('quantity_required');
    }
}