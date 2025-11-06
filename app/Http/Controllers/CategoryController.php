<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // List all categories
    public function index()
    {
        return response()->json(Category::all());
    }

    // Show single category
    public function show($id)
    {
        return response()->json(Category::findOrFail($id));
    }

    // Create category
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:categories,name',
        ]);

        $category = Category::create($validated);

        return response()->json($category, 201);
    }

    // Update category
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|unique:categories,name,'.$id,
        ]);

        $category->update($validated);

        return response()->json($category);
    }

    // Delete category
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category deleted']);
    }
}
