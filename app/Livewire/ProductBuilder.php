<?php

namespace App\Livewire;

use App\Models\ProductItem;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ProductBuilder extends Component
{
    public ProductItem $product;
    
    // Customization states
    public string $selectedSize = 'Regular';
    public array $selectedAddons = [];
    public int $quantity = 1;

    public function mount(ProductItem $product)
    {
        $this->product = $product;
    }

    public function incrementQty()
    {
        if ($this->quantity < 10) {
            $this->quantity++;
        }
    }

    public function decrementQty()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function getTotalPriceProperty()
    {
        $base = $this->product->price ?? $this->product->base_price ?? 0;
        
        // Add-on charges
        $extraPrice = 0;
        if ($this->selectedSize === 'Medium') $extraPrice += 150;
        if ($this->selectedSize === 'Large') $extraPrice += 300;

        if (in_array('Extra Cheese', $this->selectedAddons)) $extraPrice += 100;
        if (in_array('Spicy Sauce', $this->selectedAddons)) $extraPrice += 50;

        return ($base + $extraPrice) * $this->quantity;
    }

    public function addToCart()
    {
        $cart = Session::get('cart', []);

        $customizations = [
            'size' => $this->selectedSize,
            'addons' => $this->selectedAddons,
        ];

        $unitPrice = ($this->totalPrice / $this->quantity);

        // Session Cart එකට එකතු කිරීම
        $cart[] = [
            'product_id'     => $this->product->id,
            'item_name'      => $this->product->name,
            'customizations' => $customizations,
            'quantity'       => $this->quantity,
            'unit_price'     => $unitPrice,
        ];

        Session::put('cart', $cart);

        return redirect()->route('cart')->with('message', 'Customized item added to cart!');
    }

    public function render()
    {
        return view('livewire.product-builder');
    }
}