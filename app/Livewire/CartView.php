<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class CartView extends Component
{
    public array $cart = [];

    public function mount(): void
    {
        $this->cart = session()->get('cart', []);
    }

    public function removeFromCart(int $index): void
    {
        if (isset($this->cart[$index])) {
            unset($this->cart[$index]);
            $this->cart = array_values($this->cart);
            session()->put('cart', $this->cart);
        }
    }
    public function updateQuantity(int $index, int $quantity): void
{
    if (isset($this->cart[$index])) {
        $this->cart[$index]['quantity'] = max(1, $quantity);
        session()->put('cart', $this->cart);
    }
}

    public function clearCart(): void
    {
        session()->forget('cart');
        $this->cart = [];
    }

    public function render()
    {
        return view('livewire.cart-view');
    }
}