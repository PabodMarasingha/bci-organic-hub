<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::orderBy('type')->get();
        return view('admin.ingredients', compact('ingredients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'type' => 'required|in:salad_base,topping,juice_base,fruit',
            'price' => 'required|numeric|min:0',
            'calories' => 'required|integer|min:0',
        ]);

        Ingredient::create($request->all());

        return back()->with('message', 'Ingredient added.');
    }

    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();
        return back()->with('message', 'Ingredient deleted.');
    }
}