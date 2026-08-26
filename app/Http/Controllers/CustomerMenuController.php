<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\ProductItem; 
use Illuminate\Http\Request;

class CustomerMenuController extends Controller
{
    
    public function index(Request $request)
    {
       
        $types = ['All', 'Salad Base', 'Protein', 'Topping', 'Fruit', 'Juice Base', 'Dressing'];

        $query = ProductItem::query(); 

        if ($request->filled('type') && $request->type !== 'All') {
           
            $query->where('category', $request->type); 
        }
        
        $products = $query->get();

       
        $customizationOptions = Ingredient::all()->groupBy('type');

        return view('customers.menu', compact('products', 'types', 'customizationOptions'));
    }

    /**
     * Display the meal customization page.
     */
    public function build(int|string $id)
    {
        $product = ProductItem::findOrFail($id); 
        
        $customizationOptions = Ingredient::all()->groupBy('type');

        return view('customers.build', compact('product', 'customizationOptions'));
    }
}