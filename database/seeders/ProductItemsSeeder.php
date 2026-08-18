<?php

namespace Database\Seeders;

use App\Models\ProductItem;
use Illuminate\Database\Seeder;

class ProductItemsSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // Salads
            ['name' => 'Mediterranean Quinoa Salad', 'category' => 'salad', 'description' => 'Quinoa, cherry tomatoes, cucumber, feta, olives, and a lemon-olive oil dressing.', 'price' => 480, 'base_price' => 480, 'base_calories' => 320],
            ['name' => 'Roasted Beet & Spinach Salad', 'category' => 'salad', 'description' => 'Roasted beets, baby spinach, walnuts, and goat cheese with a balsamic glaze.', 'price' => 450, 'base_price' => 450, 'base_calories' => 290],
            ['name' => 'Kale Caesar Salad', 'category' => 'salad', 'description' => 'Massaged kale, parmesan, whole-grain croutons, and a light yogurt Caesar dressing.', 'price' => 420, 'base_price' => 420, 'base_calories' => 310],
            ['name' => 'Chickpea & Avocado Salad', 'category' => 'salad', 'description' => 'Chickpeas, avocado, red onion, cherry tomato, and cilantro-lime dressing.', 'price' => 400, 'base_price' => 400, 'base_calories' => 350],

            // Juices
            ['name' => 'Cold-Pressed Green Detox', 'category' => 'juice', 'description' => 'Kale, cucumber, green apple, celery, and lemon.', 'price' => 320, 'base_price' => 320, 'base_calories' => 120],
            ['name' => 'Beetroot Ginger Zing', 'category' => 'juice', 'description' => 'Beetroot, carrot, orange, and a hint of fresh ginger.', 'price' => 300, 'base_price' => 300, 'base_calories' => 140],
            ['name' => 'Tropical Turmeric Sunrise', 'category' => 'juice', 'description' => 'Pineapple, mango, turmeric, and orange.', 'price' => 310, 'base_price' => 310, 'base_calories' => 150],
            ['name' => 'Watermelon Mint Cooler', 'category' => 'juice', 'description' => 'Fresh watermelon, mint leaves, and a squeeze of lime.', 'price' => 280, 'base_price' => 280, 'base_calories' => 100],

            // Bowls
            ['name' => 'Buddha Bowl', 'category' => 'bowl', 'description' => 'Brown rice, roasted sweet potato, chickpeas, avocado, and tahini dressing.', 'price' => 520, 'base_price' => 520, 'base_calories' => 420],
            ['name' => 'Acai Berry Bowl', 'category' => 'bowl', 'description' => 'Acai blend topped with granola, banana, and organic honey.', 'price' => 480, 'base_price' => 480, 'base_calories' => 380],
            ['name' => 'Grilled Tofu Poke Bowl', 'category' => 'bowl', 'description' => 'Grilled tofu, edamame, cucumber, and brown rice with sesame-soy dressing.', 'price' => 500, 'base_price' => 500, 'base_calories' => 400],

            // Wraps
            ['name' => 'Hummus & Roasted Veggie Wrap', 'category' => 'wrap', 'description' => 'Whole-wheat wrap with hummus, grilled zucchini, bell pepper, and spinach.', 'price' => 380, 'base_price' => 380, 'base_calories' => 340],
            ['name' => 'Grilled Chicken & Avocado Wrap', 'category' => 'wrap', 'description' => 'Grilled chicken breast, avocado, lettuce, and yogurt-herb sauce in a whole-wheat wrap.', 'price' => 430, 'base_price' => 430, 'base_calories' => 380],

            // Smoothies
            ['name' => 'Berry Protein Smoothie', 'category' => 'smoothie', 'description' => 'Mixed berries, banana, oats, and plant-based protein.', 'price' => 350, 'base_price' => 350, 'base_calories' => 260],
            ['name' => 'Peanut Butter Banana Smoothie', 'category' => 'smoothie', 'description' => 'Banana, organic peanut butter, oats, and almond milk.', 'price' => 360, 'base_price' => 360, 'base_calories' => 310],
        ];

        foreach ($items as $item) {
            ProductItem::firstOrCreate(
                ['name' => $item['name']],
                $item
            );
        }
    }
}