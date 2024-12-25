<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Method untuk membuat kategori baru
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable',
            'color' => 'nullable', // Hex code for color (e.g., #FF5733)
            'budget' => 'nullable|numeric',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'icon' => $request->icon,
            'color' => $request->color,
            'budget' => $request->budget,
        ]);

        return response()->json([
            'id' => $category->id,
            'message' => 'Category created successfully.',
        ], 201);
    }

    // Method untuk mendapatkan semua kategori
    public function index()
    {
        $categories = Category::all();

        return response()->json($categories);
    }

    // Method untuk mendapatkan kategori dengan summary pengeluaran
    public function getSummary()
    {
        $categories = Category::with(['payments' => function($query) {
            $query->where('type', 'DR')->whereBetween('datetime', [now()->startOfMonth(), now()]);
        }])->get();

        $categories = $categories->map(function($category) {
            $category->expense = $category->payments->sum('amount');
            return $category;
        });

        return response()->json($categories);
    }

    // Method untuk mengupdate kategori
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $category->update($request->all());

        return response()->json(['message' => 'Category updated successfully', 'category' => $category]);
    }

    // Method untuk menghapus kategori
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }

    // Method untuk menghapus semua kategori
    public function destroyAll()
    {
        Category::query()->delete();

        return response()->json(['message' => 'All categories deleted successfully']);
    }
}
