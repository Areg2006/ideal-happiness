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

        $query->get();

        return response()->json($query);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
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
        $categories = Category::find($id);

        if (!$categories) {
            return response()->json(['message' => 'Категория не найдена'], 404);
        }

        $request->validate([
            'name' => 'required|string',
        ]);

        $categories->update($request->all());

        return response()->json($categories);
    }

    public function destroy(Request $request, int $id)
    {
        return Category::destroy($id);
    }
}









/*
{
    $categories=Category::find($id);
    if (!$categories) {
        return response()->json(['message' => 'Категория не найдена'], 404);
    }
    $request->validate([
        'name' => 'required|string',
        'id' => 'required|integer',
    ]);
    $categories->update($request->all());
    return response()->json($categories);
}*/
