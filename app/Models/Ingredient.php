<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'calories',
        'price',
        'in_stock',
    ];

    /**
     * Get product items associated with this ingredient.
     */
    public function productItems(): BelongsToMany
    {
        return $this->belongsToMany(ProductItem::class, 'ingredient_product_item')
                    ->withPivot('is_default', 'quantity')
                    ->withTimestamps();
    }
}