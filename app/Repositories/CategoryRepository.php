<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function find(int $id): ?Category
    {
        return Category::find($id);
    }

    public function getQuery()
    {
        return Category::query() ;
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(Category $category, array $data): Category
    {
        $category->update($data);
        return $category;
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }
}
