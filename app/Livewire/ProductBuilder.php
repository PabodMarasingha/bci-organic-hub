<?php

namespace App\Livewire;

use App\Models\Ingredient;
use App\Models\ProductItem;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ProductBuilder extends Component
{
    public ProductItem $product;
    public array $selectedIngredients = [];
    public bool $selectAll = false;
    public float $runningTotal = 0.0;
    public int $runningCalories = 0;
    public int $quantity = 1;
    public string $specialInstructions = '';

    /**
     * Component mount method.
     *
     * @param \App\Models\ProductItem|string|int $product
     * @return void
     */
    public function mount($product): void
    {
        if ($product instanceof ProductItem) {
            $this->product = $product;
        } else {
            $this->product = ProductItem::findOrFail($product);
        }
        
        $this->selectedIngredients = [];
        $this->selectAll = false;

        $this->calculateTotals();
    }

    /**
     * "Select All" Toggle කළ විට ක්‍රියාත්මක වේ.
     */
    public function updatedSelectAll(bool $value): void
    {
        if ($value) {
            $this->selectedIngredients = $this->getAvailableIngredients()
                ->pluck('id')
                ->map(fn($id) => (string) $id)
                ->toArray();
        } else {
            $this->selectedIngredients = [];
        }

        $this->calculateTotals();
    }

    /**
     * තනි තනි Ingredient එකක් select/deselect කළ විට ක්‍රියාත්මක වේ.
     */
    public function updatedSelectedIngredients(): void
    {
        $allIds = $this->getAvailableIngredients()
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();
        
        $this->selectAll = count($this->selectedIngredients) === count($allIds) && count($allIds) > 0;

        $this->calculateTotals();
    }

    /**
     * Quantity එක වෙනස් වන විට ක්‍රියාත්මක වේ.
     */
    public function updatedQuantity(): void
    {
        if ($this->quantity < 1 || !is_numeric($this->quantity)) {
            $this->quantity = 1;
        }

        $this->calculateTotals();
    }

    /**
     * මුළු එකතුව (Price & Calories) ගණනය කිරීම.
     */
    private function calculateTotals(): void
    {
        $ingredients = Ingredient::whereIn('id', $this->selectedIngredients)->get();

        $basePrice = (float) ($this->product->price ?? 0.0);
        $baseCalories = (int) ($this->product->base_calories ?? 0);

        $extraPrice = (float) $ingredients->sum('price');
        $extraCalories = (int) $ingredients->sum('calories');

        $this->runningTotal = (float) (($basePrice + $extraPrice) * max(1, $this->quantity));
        $this->runningCalories = (int) (($baseCalories + $extraCalories) * max(1, $this->quantity));
    }

    /**
     * Available Ingredients ලබා ගැනීම.
     */
    private function getAvailableIngredients(): Collection
    {
        // in_stock column එක නොමැති නිසා directly සියලුම ingredients ලබා ගනී
        return $this->product->ingredients()->exists() 
            ? $this->product->ingredients()->get() 
            : Ingredient::all();
    }

    /**
     * Cart එකට Item එක එකතු කිරීම.
     */
    public function addToCart()
    {
        $this->validate([
            'quantity' => 'required|integer|min:1',
            'specialInstructions' => 'nullable|string|max:500',
            'selectedIngredients' => 'array',
        ]);

        $ingredients = Ingredient::whereIn('id', $this->selectedIngredients)->get();

        $customizations = $ingredients->map(function ($ingredient) {
            return [
                'id' => $ingredient->id,
                'name' => $ingredient->name,
                'price' => (float) $ingredient->price,
                'calories' => (int) ($ingredient->calories ?? 0),
            ];
        })->toArray();

        $cart = session()->get('cart', []);

        $cart[] = [
            'cart_item_id' => uniqid('cart_'),
            'product_id' => $this->product->id,
            'item_name' => $this->product->name,
            'image' => $this->product->image,
            'quantity' => (int) $this->quantity,
            'unit_price' => (float) ($this->product->price + $ingredients->sum('price')),
            'total_price' => (float) $this->runningTotal,
            'unit_calories' => (int) ($this->product->base_calories + $ingredients->sum('calories')),
            'total_calories' => (int) $this->runningCalories,
            'customizations' => $customizations,
            'special_instructions' => $this->specialInstructions,
        ];

        session()->put('cart', $cart);

        return redirect()->route('cart')->with('message', 'Custom healthy meal added to cart successfully!');
    }

    public function render()
    {
        $ingredients = $this->getAvailableIngredients();

        return view('livewire.product-builder', compact('ingredients'));
    }
}