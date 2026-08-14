<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductItem;
use App\Models\Ingredient;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Real Organic Ingredients with Calories & LKR Price
        $ingredients = [
            // Bases & Grains
            ['name' => 'Organic Red Quinoa (100g)', 'type' => 'salad_base', 'calories' => 120, 'price' => 250.00],
            ['name' => 'Steamed Brown Rice (150g)', 'type' => 'salad_base', 'calories' => 160, 'price' => 150.00],
            ['name' => 'Fresh Mixed Greens & Kale', 'type' => 'salad_base', 'calories' => 35, 'price' => 200.00],
            
            // Proteins
            ['name' => 'Grilled Organic Chicken Breast (120g)', 'type' => 'topping', 'calories' => 195, 'price' => 450.00],
            ['name' => 'Pan-Seared Organic Tofu (100g)', 'type' => 'topping', 'calories' => 110, 'price' => 300.00],
            ['name' => 'Boiled Free-Range Egg (1 Large)', 'type' => 'topping', 'calories' => 78, 'price' => 100.00],
            ['name' => 'Seared Salmon Fillet (100g)', 'type' => 'topping', 'calories' => 206, 'price' => 850.00],
            
            // Veggies & Fiber
            ['name' => 'Avocado Slices (Half)', 'type' => 'topping', 'calories' => 160, 'price' => 250.00],
            ['name' => 'Steamed Broccoli & Carrots', 'type' => 'topping', 'calories' => 55, 'price' => 150.00],
            ['name' => 'Roasted Sweet Potato Cubes', 'type' => 'topping', 'calories' => 90, 'price' => 180.00],
            ['name' => 'Chia Seeds & Flaxseeds Mix', 'type' => 'topping', 'calories' => 60, 'price' => 120.00],
            
            // Dressing & Sauces
            ['name' => 'Extra Virgin Olive Oil & Lemon', 'type' => 'topping', 'calories' => 80, 'price' => 100.00],
            ['name' => 'Greek Yogurt Herb Dressing', 'type' => 'topping', 'calories' => 50, 'price' => 120.00],
        ];

        foreach ($ingredients as $ing) {
            Ingredient::updateOrCreate(['name' => $ing['name']], $ing);
        }

        // 2. Create Top 10 Healthy Base Meals
        $meals = [
            [
                'name' => 'Custom Organic Salad Bowl',
                'description' => 'Build your own organic meal with fresh local vegetables, grains, and protein.',
                'price' => 850.00,
                'base_calories' => 210,
                'image' => 'salad-bowl.jpg'
            ],
            [
                'name' => 'Detox Green Smoothie Bowl',
                'description' => 'Organic avocado, spinach, chia seeds, and coconut water blend.',
                'price' => 950.00,
                'base_calories' => 280,
                'image' => 'smoothie-bowl.jpg'
            ],
            [
                'name' => 'Quinoa Power Meal Bowl',
                'description' => 'Organic red quinoa with grilled tofu, steamed broccoli, and roasted nuts.',
                'price' => 1250.00,
                'base_calories' => 410,
                'image' => 'quinoa-bowl.jpg'
            ],
            [
                'name' => 'Keto Grilled Chicken Salad',
                'description' => 'High protein chicken breast, avocado, kale, and lemon olive oil dressing.',
                'price' => 1450.00,
                'base_calories' => 480,
                'image' => 'chicken-salad.jpg'
            ],
            [
                'name' => 'Mediterranean Salmon Grain Bowl',
                'description' => 'Pan-seared salmon fillet over brown rice, roasted sweet potatoes, and herbs.',
                'price' => 1850.00,
                'base_calories' => 520,
                'image' => 'salmon-bowl.jpg'
            ],
            [
                'name' => 'Vegan Wellness Buddha Bowl',
                'description' => 'A colorful mix of chickpeas, tofu, broccoli, mixed greens, and tahini.',
                'price' => 1100.00,
                'base_calories' => 350,
                'image' => 'buddha-bowl.jpg'
            ],
            [
                'name' => 'Protein Fuel Fitness Meal',
                'description' => 'Double grilled chicken, boiled eggs, sweet potatoes, and green beans.',
                'price' => 1650.00,
                'base_calories' => 590,
                'image' => 'protein-meal.jpg'
            ],
            [
                'name' => 'Organic Avocado Toast & Egg',
                'description' => 'Sourdough toast topped with mashed fresh avocado, chia seeds, and poached egg.',
                'price' => 990.00,
                'base_calories' => 320,
                'image' => 'avocado-toast.jpg'
            ],
            [
                'name' => 'Low-Carb Steamed Veggie Delight',
                'description' => 'Seasonal organic steamed vegetables tossed in herbs and olive oil.',
                'price' => 750.00,
                'base_calories' => 180,
                'image' => 'veggie-delight.jpg'
            ],
            [
                'name' => 'Berry Immunity Fruit Bowl',
                'description' => 'Fresh organic berries, banana, Greek yogurt, and flaxseed crunch.',
                'price' => 1050.00,
                'base_calories' => 260,
                'image' => 'fruit-bowl.jpg'
            ],
        ];

        foreach ($meals as $meal) {
            $productItem = ProductItem::updateOrCreate(['name' => $meal['name']], $meal);

            // Attaching ingredients to each product item
            $allIngredients = Ingredient::all();
            $productItem->ingredients()->sync($allIngredients->pluck('id'));
        }
    }
}