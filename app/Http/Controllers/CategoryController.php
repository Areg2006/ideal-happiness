<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->input('search');
        $query = Category::query();
        $query->when($searchTerm, function ($q, $searchTerm) {
            return $q->where('name', 'like', '%' . $searchTerm . '%');
        });
        $categories = $query->get()->toArray();

        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::create($validated);

        return response()->json($category, 201);
    }

    public function show(int $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Категория не найдена'], 404);
        }

        return response()->json($category);
    }

    public function update(Request $request, int $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Категория не найдена'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $category->update($validated);

        return response()->json($category);
    }

    public function destroy(int $id)
    {
        $deleted = Category::destroy($id);
        if ($deleted) {
            return response()->json(['message' => 'Категория удалена', 'deleted' => $deleted]);
        }

        return response()->json(['message' => 'Категория не найдена'], 404);
    }
}
