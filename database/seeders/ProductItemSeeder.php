<?php

namespace Database\Seeders;

use App\Models\ProductItem;
use Illuminate\Database\Seeder;

class ProductItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $meals = [
            [
                'name' => 'Organic Grilled Chicken Power Bowl',
                'description' => 'Tender grilled chicken breast served over baby spinach, feta cheese, and dressed with extra virgin olive oil.',
                'price' => 1450.00,
                'image_url' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Kale & Avocado Superfood Salad',
                'description' => 'Nutrient-rich organic kale paired with creamy avocado slices, boiled egg, and extra virgin olive oil.',
                'price' => 1350.00,
                'image_url' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Mediterranean Feta & Protein Salad',
                'description' => 'Crisp fresh lettuce, rich feta cheese, boiled eggs, and a light honey mustard drizzle.',
                'price' => 1150.00,
                'image_url' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Citrus Chicken Detox Bowl',
                'description' => 'Grilled chicken breast with fresh orange slices, baby spinach, and sweet honey mustard dressing.',
                'price' => 1500.00,
                'image_url' => 'https://images.unsplash.com/photo-1543339308-43e59d6b73a6?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Tropical Pineapple Protein Crunch',
                'description' => 'Juicy pineapple pieces combined with organic kale, grilled chicken, and healthy olive oil.',
                'price' => 1400.00,
                'image_url' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Green Apple & Feta Crisp Salad',
                'description' => 'Fresh green apple slices, baby spinach, crumbled feta cheese, and honey mustard dressing.',
                'price' => 1200.00,
                'image_url' => 'https://images.unsplash.com/photo-1505253716362-afaea1d3d1af?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Fresh Orange & Kale Immunity Bowl',
                'description' => 'Vitamin C rich orange slices, organic kale, avocado, and olive oil for peak immune health.',
                'price' => 1250.00,
                'image_url' => 'https://images.unsplash.com/photo-1623428187969-5da2dcea5ebf?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Classic Egg & Greens Fitness Salad',
                'description' => 'Double boiled eggs on a bed of fresh lettuce, baby spinach, and light honey mustard.',
                'price' => 950.00,
                'image_url' => 'https://images.unsplash.com/photo-1528825871115-3581a5387919?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Pineapple Avocado Glow Salad',
                'description' => 'Sweet pineapple paired with avocado slices, baby spinach, and extra virgin olive oil.',
                'price' => 1300.00,
                'image_url' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Ultimate Organic Garden Delight',
                'description' => 'A complete mix of spinach, lettuce, kale, green apple, orange, feta, and olive oil dressing.',
                'price' => 1600.00,
                'image_url' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        foreach ($meals as $meal) {
            ProductItem::updateOrCreate(
                ['name' => $meal['name']],
                $meal
            );
        }
    }
}