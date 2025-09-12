<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;

class CategoryService
{
    protected CategoryRepository $categoryRepo;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }
    public function listCategories(Request $request)
    {
        $searchTerm = $request->input('search');
        $query = $this->categoryRepo->getQuery();
        if ($searchTerm) {
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }

        return response()->json($query->get(), 200);
    }

    public function createCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        $category = $this->categoryRepo->create($validated);

        return response()->json($category, 201);
    }

    public function updateCategory(Request $request, int $id)
    {
        $category = $this->categoryRepo->find($id);
        if (!$category) {
            return response()->json(['message' => 'Категория не найдена'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $updated = $this->categoryRepo->update($category, $validated);

        return response()->json($updated, 200);
    }

    public function deleteCategory(int $id)
    {
        $category = $this->categoryRepo->find($id);
        if (!$category) {
            return response()->json(['message' => 'Категория не найдена'], 404);
        }

        $this->categoryRepo->delete($category);

        return response()->json(['message' => 'Категория удалена'], 200);
    }

    public function showCategory(int $id)
    {
        $category = $this->categoryRepo->find($id);
        if (!$category) {
            return response()->json(['message' => 'Категория не найдена'], 404);
        }

        return response()->json($category->load('products'), 200);
    }
}
