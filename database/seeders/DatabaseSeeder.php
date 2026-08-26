<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductItem; 
use App\Models\Ingredient;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Real Organic Ingredients
        $ingredients = [
            ['name' => 'Organic Red Quinoa (100g)', 'type' => 'Salad Base', 'calories' => 120, 'price' => 250.00],
            ['name' => 'Steamed Brown Rice (150g)', 'type' => 'Salad Base', 'calories' => 160, 'price' => 150.00],
            ['name' => 'Fresh Mixed Greens & Kale', 'type' => 'Salad Base', 'calories' => 35, 'price' => 200.00],
            ['name' => 'Grilled Organic Chicken Breast (120g)', 'type' => 'Protein', 'calories' => 195, 'price' => 450.00],
            ['name' => 'Pan-Seared Organic Tofu (100g)', 'type' => 'Protein', 'calories' => 110, 'price' => 300.00],
            ['name' => 'Boiled Free-Range Egg (1 Large)', 'type' => 'Protein', 'calories' => 78, 'price' => 100.00],
            ['name' => 'Seared Salmon Fillet (100g)', 'type' => 'Protein', 'calories' => 206, 'price' => 850.00],
            ['name' => 'Avocado Slices (Half)', 'type' => 'Topping', 'calories' => 160, 'price' => 250.00],
            ['name' => 'Steamed Broccoli & Carrots', 'type' => 'Topping', 'calories' => 55, 'price' => 150.00],
            ['name' => 'Roasted Sweet Potato Cubes', 'type' => 'Topping', 'calories' => 90, 'price' => 180.00],
            ['name' => 'Chia Seeds & Flaxseeds Mix', 'type' => 'Topping', 'calories' => 60, 'price' => 120.00],
            ['name' => 'Extra Virgin Olive Oil & Lemon', 'type' => 'Dressing', 'calories' => 80, 'price' => 100.00],
            ['name' => 'Greek Yogurt Herb Dressing', 'type' => 'Dressing', 'calories' => 50, 'price' => 120.00],
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
                'category' => 'Salad Base', // <-- 'type' වෙනුවට 'category' ලෙස නිවැරදි කර ඇත
                'base_calories' => 210,
                'image' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=500'
            ],
            [
                'name' => 'Detox Green Smoothie Bowl',
                'description' => 'Organic avocado, spinach, chia seeds, and coconut water blend.',
                'price' => 950.00,
                'category' => 'Fruit', // <-- නිවැරදි කර ඇත
                'base_calories' => 280,
                'image' => 'https://images.unsplash.com/photo-1590301157890-4810ed352733?w=500'
            ],
            [
                'name' => 'Quinoa Power Meal Bowl',
                'description' => 'Organic red quinoa with grilled tofu, steamed broccoli, and roasted nuts.',
                'price' => 1250.00,
                'category' => 'Salad Base', // <-- නිවැරදි කර ඇත
                'base_calories' => 410,
                'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500'
            ],
            [
                'name' => 'Keto Grilled Chicken Salad',
                'description' => 'High protein chicken breast, avocado, kale, and lemon olive oil dressing.',
                'price' => 1450.00,
                'category' => 'Protein', // <-- නිවැරදි කර ඇත
                'base_calories' => 480,
                'image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=500'
            ],
            [
                'name' => 'Mediterranean Salmon Grain Bowl',
                'description' => 'Pan-seared salmon fillet over brown rice, roasted sweet potatoes, and herbs.',
                'price' => 1850.00,
                'category' => 'Protein', // <-- නිවැරදි කර ඇත
                'base_calories' => 520,
                'image' => 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=500'
            ],
            [
                'name' => 'Vegan Wellness Buddha Bowl',
                'description' => 'A colorful mix of chickpeas, tofu, broccoli, mixed greens, and tahini.',
                'price' => 1100.00,
                'category' => 'Salad Base', // <-- නිවැරදි කර ඇත
                'base_calories' => 350,
                'image' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=500'
            ],
            [
                'name' => 'Protein Fuel Fitness Meal',
                'description' => 'Double grilled chicken, boiled eggs, sweet potatoes, and green beans.',
                'price' => 1650.00,
                'category' => 'Protein', // <-- නිවැරදි කර ඇත
                'base_calories' => 590,
                'image' => 'https://images.unsplash.com/photo-1543339308-43e59d6b73a6?w=500'
            ],
            [
                'name' => 'Organic Avocado Toast & Egg',
                'description' => 'Sourdough toast topped with mashed fresh avocado, chia seeds, and poached egg.',
                'price' => 990.00,
                'category' => 'Topping', // <-- නිවැරදි කර ඇත
                'base_calories' => 320,
                'image' => 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=500'
            ],
            [
                'name' => 'Low-Carb Steamed Veggie Delight',
                'description' => 'Seasonal organic steamed vegetables tossed in herbs and olive oil.',
                'price' => 750.00,
                'category' => 'Topping', // <-- නිවැරදි කර ඇත
                'base_calories' => 180,
                'image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=500'
            ],
            [
                'name' => 'Berry Immunity Fruit Bowl',
                'description' => 'Fresh organic berries, banana, Greek yogurt, and flaxseed crunch.',
                'price' => 1050.00,
                'category' => 'Fruit', // <-- නිවැරදි කර ඇත
                'base_calories' => 260,
                'image' => 'https://images.unsplash.com/photo-1511690656952-34342bb7c2f2?w=500'
            ],
        ];

        foreach ($meals as $meal) {
            $product = ProductItem::updateOrCreate(['name' => $meal['name']], $meal);

            if (method_exists($product, 'ingredients')) {
                $allIngredients = Ingredient::all();
                $product->ingredients()->sync($allIngredients->pluck('id'));
            }
        }
    }
}