<?php

namespace App\Livewire;

use App\Models\DeliveryZone;
use Livewire\Component;

class Checkout extends Component
{
    public array $cart = [];
    public string $deliveryZone = '';
    public string $dropoffLocation = '';
    public string $paymentMethod = 'Campus Digital Wallet';
    public string $specialInstructions = '';

    public float $subtotal = 0.0;
    public int $totalCalories = 0;
    public float $deliveryFee = 0.0;
    public float $grandTotal = 0.0;

    public function mount(): void
    {
        $this->cart = session()->get('cart', []);
        $this->calculateTotals();
    }

    /**
     * Delivery Zone එක වෙනස් කළ විට delivery fee එක auto update වේ.
     */
    public function updatedDeliveryZone(mixed $value): void
    {
        $zone = DeliveryZone::find($value);

        if ($zone) {
            $this->deliveryFee = (float) ($zone->delivery_fee ?? 0.0);
        } else {
            $this->deliveryFee = 0.0;
        }

        $this->calculateTotals();
    }

    private function calculateTotals(): void
    {
        $this->subtotal = array_reduce($this->cart, function ($carry, $item) {
            return $carry + ($item['unit_price'] * $item['quantity']);
        }, 0.0);

        $this->totalCalories = array_reduce($this->cart, function ($carry, $item) {
            return $carry + ($item['total_calories'] ?? 0);
        }, 0);

        $this->grandTotal = $this->subtotal + $this->deliveryFee;
    }

    public function placeOrder()
    {
        $this->validate([
            'deliveryZone' => 'required',
            'dropoffLocation' => 'required|string|max:255',
            'paymentMethod' => 'required',
        ]);

        // Place order backend DB save logic goes here (e.g., saving to orders table)

        session()->forget('cart');
        return redirect()->route('dashboard')->with('message', 'Order placed successfully!');
    }

    public function render()
    {
        // Database එකෙන් Delivery Zones ලබා ගැනීම
        $deliveryZones = DeliveryZone::all();

        // Database එක හිස් නම් UI එක කැඩී යාම වැළැක්වීමට fallback data එකක් ලබාදීම
        if ($deliveryZones->isEmpty()) {
            $deliveryZones = collect([
                (object)['id' => 1, 'name' => 'Main Academic Building', 'delivery_fee' => 0.00],
                (object)['id' => 2, 'name' => 'Faculty of IT & Computing', 'delivery_fee' => 0.00],
                (object)['id' => 3, 'name' => 'Library & Study Zone', 'delivery_fee' => 0.00],
                (object)['id' => 4, 'name' => 'Student Hostel Complex', 'delivery_fee' => 50.00],
            ]);
        }

        return view('livewire.checkout', [
            'deliveryZones' => $deliveryZones
        ]);
    }
}