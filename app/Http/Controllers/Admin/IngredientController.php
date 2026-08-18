<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    public function index()
    {
        // Low stock alerts ඇතුළුව Type සහ Name අනුව Sort කර ලබාගැනීම
        $ingredients = Ingredient::orderBy('type')->orderBy('name')->get();
        return view('admin.ingredients', compact('ingredients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:salad_base,topping,juice_base,fruit,protein,dressing',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|in:kg,g,l,ml,pcs,pack',
            'reorder_level' => 'required|numeric|min:0',
            'calories' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'in_stock' => 'nullable|boolean',
        ]);

        $validated['in_stock'] = $request->has('in_stock') ? true : true;

        Ingredient::create($validated);

        return back()->with('success', 'Ingredient added to inventory successfully.');
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:salad_base,topping,juice_base,fruit,protein,dressing',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|in:kg,g,l,ml,pcs,pack',
            'reorder_level' => 'required|numeric|min:0',
            'calories' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'in_stock' => 'nullable|boolean',
        ]);

        $validated['in_stock'] = $request->boolean('in_stock');

        $ingredient->update($validated);

        return back()->with('success', 'Ingredient updated successfully.');
    }

    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();
        return back()->with('success', 'Ingredient removed from inventory successfully.');
    }
}