<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\ProductItem;
use Illuminate\Database\Seeder;

class ProductAndIngredientSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================
        // 1. PRODUCTS / FOOD ITEMS (5 Categories x 5 Items = 25 Items)
        // ==========================================
        $products = [
            // --- Category 1: Signature Salad Bowls ---
            [
                'name' => 'Custom Organic Salad Bowl',
                'description' => '[Salad Bowls] Build your own organic meal with fresh local vegetables, grains, and protein.',
                'price' => 850.00,
                'base_calories' => 250,
                'image' => 'custom-salad.jpg',
            ],
            [
                'name' => 'Quinoa Power Bowl',
                'description' => '[Salad Bowls] Organic red quinoa with grilled tofu, steamed broccoli, and roasted nuts.',
                'price' => 1250.00,
                'base_calories' => 410,
                'image' => 'quinoa-power.jpg',
            ],
            [
                'name' => 'Mediterranean Kale Salad',
                'description' => '[Salad Bowls] Crisp kale leaves, olives, feta, and cucumber tossed in a light vinaigrette.',
                'price' => 1100.00,
                'base_calories' => 280,
                'image' => null,
            ],
            [
                'name' => 'Avocado & Egg Protein Bowl',
                'description' => '[Salad Bowls] Protein-packed bowl with boiled eggs, creamy avocado, and mixed greens.',
                'price' => 1350.00,
                'base_calories' => 390,
                'image' => null,
            ],
            [
                'name' => 'Spicy Chickpea Salad',
                'description' => '[Salad Bowls] Roasted chickpeas on a bed of fresh lettuce with a spicy tahini dressing.',
                'price' => 950.00,
                'base_calories' => 310,
                'image' => null,
            ],

            // --- Category 2: Detox & Smoothie Bowls ---
            [
                'name' => 'Detox Green Smoothie Bowl',
                'description' => '[Smoothie Bowls] Organic avocado, spinach, chia seeds, and coconut water blend.',
                'price' => 950.00,
                'base_calories' => 290,
                'image' => 'green-smoothie.jpg',
            ],
            [
                'name' => 'Berry Blast Açai Bowl',
                'description' => '[Smoothie Bowls] Mixed berries blended with almond milk, topped with chia seeds.',
                'price' => 1150.00,
                'base_calories' => 250,
                'image' => null,
            ],
            [
                'name' => 'Tropical Mango Smoothie Bowl',
                'description' => '[Smoothie Bowls] Sweet mango and pineapple smoothie bowl topped with coconut flakes.',
                'price' => 1250.00,
                'base_calories' => 280,
                'image' => null,
            ],
            [
                'name' => 'Nutty Banana Protein Bowl',
                'description' => '[Smoothie Bowls] Banana blended with peanut butter, topped with crushed almonds.',
                'price' => 1050.00,
                'base_calories' => 420,
                'image' => null,
            ],
            [
                'name' => 'Dragon Fruit Glow Bowl',
                'description' => '[Smoothie Bowls] Vibrant pink dragon fruit blend with kiwi slices and organic granola.',
                'price' => 1400.00,
                'base_calories' => 270,
                'image' => null,
            ],

            // --- Category 3: Healthy Wraps ---
            [
                'name' => 'Grilled Chicken Wrap',
                'description' => '[Healthy Wraps] Lean grilled chicken breast with fresh lettuce in a whole wheat wrap.',
                'price' => 950.00,
                'base_calories' => 350,
                'image' => null,
            ],
            [
                'name' => 'Vegan Hummus & Veggie Wrap',
                'description' => '[Healthy Wraps] Creamy hummus, cucumber, and shredded carrots in a spinach wrap.',
                'price' => 850.00,
                'base_calories' => 290,
                'image' => null,
            ],
            [
                'name' => 'Spinach & Feta Wrap',
                'description' => '[Healthy Wraps] Warm wrap filled with baby spinach, feta cheese, and roasted tomatoes.',
                'price' => 900.00,
                'base_calories' => 310,
                'image' => null,
            ],
            [
                'name' => 'Spicy Tuna Wrap',
                'description' => '[Healthy Wraps] Fresh tuna mixed with spicy greek yogurt sauce and crisp greens.',
                'price' => 1100.00,
                'base_calories' => 340,
                'image' => null,
            ],
            [
                'name' => 'Avocado Turkey Wrap',
                'description' => '[Healthy Wraps] Sliced turkey breast, fresh avocado, and honey mustard dressing.',
                'price' => 1200.00,
                'base_calories' => 380,
                'image' => null,
            ],

            // --- Category 4: Cold-Pressed Fresh Juices ---
            [
                'name' => 'Citrus Sunrise Juice',
                'description' => '[Fresh Juices] 100% freshly squeezed oranges with a hint of ginger.',
                'price' => 650.00,
                'base_calories' => 120,
                'image' => null,
            ],
            [
                'name' => 'Pure Green Detox Juice',
                'description' => '[Fresh Juices] Cold-pressed kale, celery, green apple, and lemon juice.',
                'price' => 750.00,
                'base_calories' => 90,
                'image' => null,
            ],
            [
                'name' => 'Beetroot Energizer',
                'description' => '[Fresh Juices] Earthy beetroot blended with carrot and apple for natural energy.',
                'price' => 700.00,
                'base_calories' => 110,
                'image' => null,
            ],
            [
                'name' => 'Pineapple Mint Splash',
                'description' => '[Fresh Juices] Refreshing tropical pineapple juice infused with fresh mint leaves.',
                'price' => 600.00,
                'base_calories' => 130,
                'image' => null,
            ],
            [
                'name' => 'Carrot Apple Zing',
                'description' => '[Fresh Juices] Sweet carrots and crisp apples juiced to perfection.',
                'price' => 650.00,
                'base_calories' => 115,
                'image' => null,
            ],

            // --- Category 5: Hot Protein Plates ---
            [
                'name' => 'Lemon Herb Chicken Plate',
                'description' => '[Protein Plates] Grilled chicken breast marinated in herbs, served with steamed broccoli.',
                'price' => 1800.00,
                'base_calories' => 450,
                'image' => null,
            ],
            [
                'name' => 'Grilled Salmon & Quinoa',
                'description' => '[Protein Plates] Fresh grilled salmon fillet served over a bed of organic quinoa.',
                'price' => 2500.00,
                'base_calories' => 520,
                'image' => null,
            ],
            [
                'name' => 'Tofu Stir-Fry Plate',
                'description' => '[Protein Plates] Organic tofu stir-fried with mixed vegetables in a light soy sauce.',
                'price' => 1400.00,
                'base_calories' => 340,
                'image' => null,
            ],
            [
                'name' => 'Lean Beef & Brown Rice',
                'description' => '[Protein Plates] Sautéed lean beef strips with garlic, served with brown rice.',
                'price' => 2100.00,
                'base_calories' => 580,
                'image' => null,
            ],
            [
                'name' => 'Baked Cod with Asparagus',
                'description' => '[Protein Plates] Tender baked cod fish paired with roasted asparagus spears.',
                'price' => 2200.00,
                'base_calories' => 390,
                'image' => null,
            ],
        ];

        foreach ($products as $product) {
            ProductItem::create($product);
        }

        // ==========================================
        // 2. INGREDIENTS
        // ==========================================
        $ingredients = [
            // Salad Bases & Grains
            ['name' => 'Baby Spinach', 'type' => 'SALAD BASE', 'stock_quantity' => 18.5, 'calories' => 23, 'price' => 100.00, 'in_stock' => true],
            ['name' => 'Fresh Mixed Greens / Kale', 'type' => 'SALAD BASE', 'stock_quantity' => 25.0, 'calories' => 15, 'price' => 200.00, 'in_stock' => true],
            ['name' => 'Organic Red Quinoa', 'type' => 'BASE', 'stock_quantity' => 15.0, 'calories' => 120, 'price' => 250.00, 'in_stock' => true],
            ['name' => 'Brown Rice', 'type' => 'BASE', 'stock_quantity' => 20.0, 'calories' => 110, 'price' => 150.00, 'in_stock' => true],

            // Proteins
            ['name' => 'Grilled Organic Chicken Breast', 'type' => 'PROTEIN', 'stock_quantity' => 30.0, 'calories' => 165, 'price' => 400.00, 'in_stock' => true],
            ['name' => 'Organic Tofu / Paneer', 'type' => 'PROTEIN', 'stock_quantity' => 12.0, 'calories' => 140, 'price' => 300.00, 'in_stock' => true],
            ['name' => 'Boiled Organic Egg', 'type' => 'PROTEIN', 'stock_quantity' => 60.0, 'calories' => 78, 'price' => 100.00, 'in_stock' => true],
            
            // Toppings & Veggies
            ['name' => 'Avocado Slices', 'type' => 'TOPPING', 'stock_quantity' => 50.0, 'calories' => 160, 'price' => 200.00, 'in_stock' => true],
            ['name' => 'Steamed Broccoli & Carrot', 'type' => 'TOPPING', 'stock_quantity' => 22.0, 'calories' => 55, 'price' => 150.00, 'in_stock' => true],
            ['name' => 'Organic Cherry Tomatoes', 'type' => 'TOPPING', 'stock_quantity' => 18.0, 'calories' => 18, 'price' => 120.00, 'in_stock' => true],
            ['name' => 'Feta Cheese', 'type' => 'TOPPING', 'stock_quantity' => 8.0, 'calories' => 264, 'price' => 250.00, 'in_stock' => true],
            ['name' => 'Chia & Pumpkin Seeds', 'type' => 'TOPPING', 'stock_quantity' => 5.0, 'calories' => 180, 'price' => 100.00, 'in_stock' => true],

            // Dressings & Dips
            ['name' => 'Extra Virgin Olive Oil & Lemon', 'type' => 'DRESSING', 'stock_quantity' => 15.0, 'calories' => 119, 'price' => 80.00, 'in_stock' => true],
            ['name' => 'Organic Honey Mustard', 'type' => 'DRESSING', 'stock_quantity' => 10.0, 'calories' => 130, 'price' => 100.00, 'in_stock' => true],
            ['name' => 'Greek Yogurt & Garlic Dip', 'type' => 'DRESSING', 'stock_quantity' => 12.0, 'calories' => 90, 'price' => 120.00, 'in_stock' => true],

            // Fruits / Juices
            ['name' => 'Fresh Orange', 'type' => 'JUICE BASE', 'stock_quantity' => 35.0, 'calories' => 47, 'price' => 150.00, 'in_stock' => true],
            ['name' => 'Pineapple', 'type' => 'JUICE BASE', 'stock_quantity' => 20.0, 'calories' => 50, 'price' => 180.00, 'in_stock' => true],
            ['name' => 'Green Apple', 'type' => 'FRUIT', 'stock_quantity' => 40.0, 'calories' => 52, 'price' => 120.00, 'in_stock' => true],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create($ingredient);
        }
    }
}