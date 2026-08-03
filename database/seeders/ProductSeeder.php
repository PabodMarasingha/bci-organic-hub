<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductItem;
use App\Models\Ingredient;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create or Get Ingredients (Removed 'unit' column to match table schema)
        $lettuce = Ingredient::firstOrCreate(['name' => 'Organic Lettuce'], ['in_stock' => true]);
        $cheese = Ingredient::firstOrCreate(['name' => 'Cheddar Cheese'], ['in_stock' => true]);
        $patty = Ingredient::firstOrCreate(['name' => 'Veggie Patty'], ['in_stock' => true]);
        $avocado = Ingredient::firstOrCreate(['name' => 'Fresh Avocado'], ['in_stock' => true]);
        $tomato = Ingredient::firstOrCreate(['name' => 'Organic Tomato'], ['in_stock' => true]);

        // 2. Seed Sample Products
        $products = [
            [
                'name' => 'Classic Organic Burger',
                'description' => 'A juicy plant-based veggie patty with organic lettuce, tomatoes, and melted cheddar cheese.',
                'price' => 1250.00,
                'category' => 'burgers',
                'is_available' => true,
                'ingredients' => [$lettuce->id, $cheese->id, $patty->id, $tomato->id],
            ],
            [
                'name' => 'Avocado Power Bowl',
                'description' => 'Nutritious bowl filled with sliced fresh avocado, organic greens, and homemade dressing.',
                'price' => 1800.00,
                'category' => 'organic_bowls',
                'is_available' => true,
                'ingredients' => [$avocado->id, $lettuce->id, $tomato->id],
            ],
            [
                'name' => 'Double Cheese Veggie Burger',
                'description' => 'Loaded with double layers of cheddar cheese, fresh veggies, and special house sauce.',
                'price' => 1550.00,
                'category' => 'burgers',
                'is_available' => true,
                'ingredients' => [$lettuce->id, $cheese->id, $patty->id],
            ],
            [
                'name' => 'Green Detox Smoothies',
                'description' => 'Cold-pressed fresh organic green apple, spinach, and celery juice.',
                'price' => 850.00,
                'category' => 'beverages',
                'is_available' => true,
                'ingredients' => [],
            ],
        ];

        // 3. Save Products & Attach Ingredients
        foreach ($products as $productData) {
            $ingredients = $productData['ingredients'];
            unset($productData['ingredients']);

            $product = ProductItem::firstOrCreate(
                ['name' => $productData['name']],
                $productData
            );

            if (method_exists($product, 'ingredients') && !empty($ingredients)) {
                $product->ingredients()->syncWithoutDetaching($ingredients);
            }
        }
    }
}