<?php

use Livewire\Component;
use App\Models\Ingredient;
use App\Models\ProductItem;
use Illuminate\Support\Facades\Session;

new class extends Component
{
    public ProductItem $product;
    public array $selectedIngredients = [];
    public float $runningTotal = 0;
    public int $runningCalories = 0;

    public function mount(ProductItem $product)
    {
        $this->product = $product;
        $this->runningTotal = $product->base_price;
    }

    public function toggleIngredient($ingredientId)
    {
        if (in_array($ingredientId, $this->selectedIngredients)) {
            $this->selectedIngredients = array_diff($this->selectedIngredients, [$ingredientId]);
        } else {
            $this->selectedIngredients[] = $ingredientId;
        }
        $this->recalculate();
    }

    public function recalculate()
    {
        $ingredients = Ingredient::whereIn('id', $this->selectedIngredients)->get();
        $this->runningTotal = $this->product->base_price + $ingredients->sum('price');
        $this->runningCalories = $ingredients->sum('calories');
    }

    public function addToCart()
    {
        $ingredientNames = Ingredient::whereIn('id', $this->selectedIngredients)->pluck('name')->toArray();

        $cart = Session::get('cart', []);
        $cart[] = [
            'product_id' => $this->product->id,
            'item_name' => $this->product->name,
            'customizations' => $ingredientNames,
            'quantity' => 1,
            'unit_price' => $this->runningTotal,
        ];
        Session::put('cart', $cart);

        session()->flash('message', 'Added to cart!');
        $this->selectedIngredients = [];
        $this->recalculate();
    }

    public function with(): array
    {
        return [
            'ingredientsByType' => Ingredient::where('in_stock', true)->get()->groupBy('type'),
        ];
    }
};
?>

<div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-4">{{ $product->name }}</h2>

    @if (session()->has('message'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('message') }}</div>
    @endif

    @foreach ($ingredientsByType as $type => $ingredients)
        <h3 class="font-semibold mt-4 mb-2 capitalize">{{ str_replace('_', ' ', $type) }}</h3>
        <div class="grid grid-cols-2 gap-2">
            @foreach ($ingredients as $ingredient)
                <label class="flex items-center gap-2 border p-2 rounded cursor-pointer">
                    <input type="checkbox"
                        wire:click="toggleIngredient({{ $ingredient->id }})"
                        @checked(in_array($ingredient->id, $selectedIngredients))>
                    {{ $ingredient->name }} (+Rs. {{ $ingredient->price }}, {{ $ingredient->calories }} kcal)
                </label>
            @endforeach
        </div>
    @endforeach

    <div class="mt-6 border-t pt-4 flex justify-between items-center">
        <div>
            <p class="text-lg">Total: <strong>Rs. {{ number_format($runningTotal, 2) }}</strong></p>
            <p class="text-sm text-gray-500">{{ $runningCalories }} kcal</p>
        </div>
        <button wire:click="addToCart" class="bg-green-600 text-white px-4 py-2 rounded">Add to Cart</button>
    </div>
</div>