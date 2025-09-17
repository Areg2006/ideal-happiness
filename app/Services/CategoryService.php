<?php

namespace App\Services;

use App\DTO\CategoryStoreDTO;
use App\DTO\CategoryUpdateDTO;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Auth;

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

    public function createCategory(CategoryStoreDTO $dto)
    {
        $authUser = Auth::user();
        if (!$authUser) {
            abort(401, 'Вы не авторизованы');
        }
        return Category::create([
            'name' => $dto->name,
        ]);
    }

    public function updateCategory(int $id, CategoryUpdateDTO $dto): Category
    {
        $category = $this->categoryRepo->find($id);
        if (!$category) {
            abort(404, 'Категория не найдена');
        }
        $category->update([
            'id' => $dto->id,
            'name' => $dto->name,
        ]);
        return $category;
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
