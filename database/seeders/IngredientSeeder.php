<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        $ingredients = [
            // Salad Bases
            ['name' => 'Fresh Lettuce', 'type' => 'salad_base', 'quantity' => 25.00, 'unit' => 'kg', 'reorder_level' => 5.00, 'calories' => 15, 'price' => 450.00, 'in_stock' => true],
            ['name' => 'Baby Spinach', 'type' => 'salad_base', 'quantity' => 18.50, 'unit' => 'kg', 'reorder_level' => 4.00, 'calories' => 23, 'price' => 600.00, 'in_stock' => true],
            ['name' => 'Organic Kale', 'type' => 'salad_base', 'quantity' => 12.00, 'unit' => 'kg', 'reorder_level' => 3.00, 'calories' => 49, 'price' => 850.00, 'in_stock' => true],

            // Proteins & Toppings
            ['name' => 'Grilled Chicken Breast', 'type' => 'protein', 'quantity' => 30.00, 'unit' => 'kg', 'reorder_level' => 8.00, 'calories' => 165, 'price' => 1400.00, 'in_stock' => true],
            ['name' => 'Avocado Slices', 'type' => 'topping', 'quantity' => 50.00, 'unit' => 'pcs', 'reorder_level' => 10.00, 'calories' => 160, 'price' => 250.00, 'in_stock' => true],
            ['name' => 'Feta Cheese', 'type' => 'topping', 'quantity' => 8.00, 'unit' => 'kg', 'reorder_level' => 2.00, 'calories' => 264, 'price' => 2200.00, 'in_stock' => true],
            ['name' => 'Boiled Eggs', 'type' => 'topping', 'quantity' => 60.00, 'unit' => 'pcs', 'reorder_level' => 15.00, 'calories' => 78, 'price' => 60.00, 'in_stock' => true],

            // Fruits & Juice Bases
            ['name' => 'Green Apple', 'type' => 'fruit', 'quantity' => 40.00, 'unit' => 'pcs', 'reorder_level' => 10.00, 'calories' => 52, 'price' => 180.00, 'in_stock' => true],
            ['name' => 'Fresh Orange', 'type' => 'juice_base', 'quantity' => 35.00, 'unit' => 'kg', 'reorder_level' => 8.00, 'calories' => 47, 'price' => 750.00, 'in_stock' => true],
            ['name' => 'Pineapple', 'type' => 'juice_base', 'quantity' => 20.00, 'unit' => 'pcs', 'reorder_level' => 5.00, 'calories' => 50, 'price' => 350.00, 'in_stock' => true],

            // Dressings
            ['name' => 'Extra Virgin Olive Oil', 'type' => 'dressing', 'quantity' => 15.00, 'unit' => 'l', 'reorder_level' => 3.00, 'calories' => 884, 'price' => 3200.00, 'in_stock' => true],
            ['name' => 'Honey Mustard Dressing', 'type' => 'dressing', 'quantity' => 10.00, 'unit' => 'l', 'reorder_level' => 2.00, 'calories' => 460, 'price' => 1800.00, 'in_stock' => true],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create($ingredient);
        }
    }
}