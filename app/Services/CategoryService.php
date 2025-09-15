<?php

namespace App\Services;

use App\Repositories\CategoryRepository;

class CategoryService
{
    protected CategoryRepository $categoryRepo;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function listCategories(?string $searchTerm = null)
    {
        $query = $this->categoryRepo->getQuery();

        if ($searchTerm) {
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }

        return response()->json($query->get(), 200);
    }

    public function createCategory(array $data)
    {
        $category = $this->categoryRepo->create($data);

        return response()->json($category, 201);
    }

    public function updateCategory(array $data, int $id)
    {
        $category = $this->categoryRepo->find($id);
        if (!$category) {
            return response()->json(['message' => 'Категория не найдена'], 404);
        }

        $updated = $this->categoryRepo->update($category, $data);

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
