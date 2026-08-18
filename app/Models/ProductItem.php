<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'base_calories',
        'image',
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
}