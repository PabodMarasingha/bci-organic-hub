<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Database\Seeder;

class HealthyMenuSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Real-world Ingredients + Calories (Per 100g / Serving)
        $ingredients = [
            // Salad Base
            ['name' => 'Baby Spinach', 'type' => 'Salad Base', 'calories' => 23, 'price' => 150.00],
            ['name' => 'Crisp Romaine Lettuce', 'type' => 'Salad Base', 'calories' => 17, 'price' => 120.00],
            ['name' => 'Fresh Kale', 'type' => 'Salad Base', 'calories' => 33, 'price' => 180.00],
            
            // Protein
            ['name' => 'Grilled Chicken Breast', 'type' => 'Protein', 'calories' => 165, 'price' => 450.00],
            ['name' => 'Boiled Eggs (2x)', 'type' => 'Protein', 'calories' => 155, 'price' => 100.00],
            ['name' => 'Organic Tofu Cubes', 'type' => 'Protein', 'calories' => 76, 'price' => 250.00],
            ['name' => 'Smoked Salmon', 'type' => 'Protein', 'calories' => 206, 'price' => 650.00],
            
            // Topping
            ['name' => 'Roasted Almond Flakes', 'type' => 'Topping', 'calories' => 160, 'price' => 200.00],
            ['name' => 'Chia Seeds', 'type' => 'Topping', 'calories' => 138, 'price' => 150.00],
            ['name' => 'Pumpkin Seeds', 'type' => 'Topping', 'calories' => 151, 'price' => 180.00],
            ['name' => 'Feta Cheese', 'type' => 'Topping', 'calories' => 75, 'price' => 220.00],
            
            // Fruit
            ['name' => 'Avocado Slices', 'type' => 'Fruit', 'calories' => 160, 'price' => 250.00],
            ['name' => 'Green Apple Dices', 'type' => 'Fruit', 'calories' => 52, 'price' => 150.00],
            ['name' => 'Fresh Blueberries', 'type' => 'Fruit', 'calories' => 57, 'price' => 300.00],
            ['name' => 'Pineapple Chunks', 'type' => 'Fruit', 'calories' => 50, 'price' => 120.00],
            
            // Juice Base
            ['name' => 'Pure Coconut Water', 'type' => 'Juice Base', 'calories' => 45, 'price' => 150.00],
            ['name' => 'Unsweetened Almond Milk', 'type' => 'Juice Base', 'calories' => 30, 'price' => 200.00],
            ['name' => 'Fresh Orange Juice', 'type' => 'Juice Base', 'calories' => 112, 'price' => 180.00],
            
            // Dressing
            ['name' => 'Extra Virgin Olive Oil & Lemon', 'type' => 'Dressing', 'calories' => 119, 'price' => 100.00],
            ['name' => 'Greek Yogurt Garlic Sauce', 'type' => 'Dressing', 'calories' => 60, 'price' => 120.00],
            ['name' => 'Honey Mustard Vinaigrette', 'type' => 'Dressing', 'calories' => 90, 'price' => 110.00],
        ];

        foreach ($ingredients as $item) {
            Ingredient::updateOrCreate(['name' => $item['name']], $item);
        }

        // 2. Pre-made Healthy Menu Items 10
        $products = [
            ['name' => 'Keto Chicken Avocado Bowl', 'category' => 'Salad Base', 'calories' => 460, 'price' => 1250.00, 'description' => 'Romaine lettuce, grilled chicken, avocado, feta cheese & olive oil.'],
            ['name' => 'High-Protein Salmon Fiesta', 'category' => 'Protein', 'calories' => 520, 'price' => 1650.00, 'description' => 'Smoked salmon, spinach, boiled egg, pumpkin seeds with Greek yogurt sauce.'],
            ['name' => 'Vegan Power Tofu Crunch', 'category' => 'Salad Base', 'calories' => 340, 'price' => 980.00, 'description' => 'Organic tofu, kale, green apple, almond flakes & honey mustard.'],
            ['name' => 'Tropical Blueberry Smoothie Bowl', 'category' => 'Fruit', 'calories' => 310, 'price' => 890.00, 'description' => 'Almond milk base with blueberries, chia seeds, and pineapple.'],
            ['name' => 'Clean Green Detox Juice', 'category' => 'Juice Base', 'calories' => 140, 'price' => 650.00, 'description' => 'Coconut water, green apple, spinach & lemon juice blend.'],
            ['name' => 'Mediterranean Egg & Feta Salad', 'category' => 'Salad Base', 'calories' => 380, 'price' => 920.00, 'description' => 'Boiled eggs, spinach, feta cheese, and olive oil dressing.'],
            ['name' => 'Skinny Avocado Citrus Splash', 'category' => 'Fruit', 'calories' => 290, 'price' => 850.00, 'description' => 'Fresh avocado, orange juice, and chia seeds.'],
            ['name' => 'Nutty Protein Booster Salad', 'category' => 'Protein', 'calories' => 490, 'price' => 1150.00, 'description' => 'Chicken breast, almonds, pumpkin seeds, romaine lettuce with Greek yogurt.'],
            ['name' => 'Hydrating Coconut Green Splash', 'category' => 'Juice Base', 'calories' => 125, 'price' => 580.00, 'description' => 'Pure coconut water infused with fresh mint and green apples.'],
            ['name' => 'Lean Smoked Salmon Roll Salad', 'category' => 'Protein', 'calories' => 410, 'price' => 1480.00, 'description' => 'Smoked salmon, baby spinach, avocado, and lemon vinaigrette.'],
        ];

        foreach ($products as $prod) {
            Product::updateOrCreate(['name' => $prod['name']], $prod);
        }
    }
}