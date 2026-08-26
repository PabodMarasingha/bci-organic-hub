<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Review;

class ProductItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'base_calories',
        'image',
        'average_rating', 
        'reviews_count',  
    ];

    /**
     * Get the ingredients for the product item.
     */
    public function ingredients(): BelongsToMany
    {
        // 100% Matching Pivot Table Name: ingredient_product_item
        return $this->belongsToMany(Ingredient::class, 'ingredient_product_item')
                    ->withPivot('is_default', 'quantity')
                    ->withTimestamps();
    }

    /**
     * Get the reviews for the product item.
     */
    public function reviews(): HasMany
    {
        
       return $this->hasMany(\App\Models\Review::class, 'product_item_id', 'id');
    }
}