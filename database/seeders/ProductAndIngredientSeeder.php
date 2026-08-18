<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\ProductItem;
use Illuminate\Database\Seeder;

class ProductAndIngredientSeeder extends Seeder
{
    public function run(): void
    {
        ProductItem::create([
            'name' => 'Custom Organic Salad Bowl',
            'description' => 'Build your own organic meal with fresh local vegetables, grains, and protein.',
            'price' => 850.00,
            'image' => 'custom-salad.jpg',
        ]);

        ProductItem::create([
            'name' => 'Detox Green Smoothie Bowl',
            'description' => 'Organic avocado, spinach, chia seeds, and coconut water blend.',
            'price' => 950.00,
            'image' => 'green-smoothie.jpg',
        ]);

        ProductItem::create([
            'name' => 'Quinoa Power Bowl',
            'description' => 'Organic red quinoa with grilled tofu, steamed broccoli, and roasted nuts.',
            'price' => 1250.00,
            'image' => 'quinoa-power.jpg',
        ]);

        $ingredients = [
            ['name' => 'Organic Red Quinoa', 'price' => 250.00],
            ['name' => 'Brown Rice', 'price' => 150.00],
            ['name' => 'Fresh Mixed Greens / Kale', 'price' => 200.00],
            ['name' => 'Grilled Organic Chicken Breast', 'price' => 400.00],
            ['name' => 'Organic Tofu / Paneer', 'price' => 300.00],
            ['name' => 'Boiled Organic Egg', 'price' => 100.00],
            ['name' => 'Avocado Slices', 'price' => 200.00],
            ['name' => 'Steamed Broccoli & Carrot', 'price' => 150.00],
            ['name' => 'Organic Cherry Tomatoes', 'price' => 120.00],
            ['name' => 'Chia & Pumpkin Seeds', 'price' => 100.00],
            ['name' => 'Extra Virgin Olive Oil & Lemon', 'price' => 80.00],
            ['name' => 'Organic Honey Mustard', 'price' => 100.00],
            ['name' => 'Greek Yogurt & Garlic Dip', 'price' => 120.00],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create($ingredient);
        }
    }
}