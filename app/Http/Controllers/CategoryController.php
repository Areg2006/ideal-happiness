<?php

namespace App\Http\Controllers;

use App\DTO\CategoryStoreDTO;
use App\DTO\CategoryUpdateDTO;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected CategoryService $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return $this->service->listCategories($request);
    }

    public function store(CategoryStoreRequest $request)
    {
        $dto = new CategoryStoreDTO(
            name: $request->getName,
        );
        $category = $this->service->createCategory($dto);
        return response()->json($category);
    }

    public function show(int $id)
    {
        return $this->service->showCategory($id);
    }

    public function update(CategoryUpdateRequest $request)
    {
        $dto = new CategoryUpdateDTO(
            id: $request->getID(),
            name: $request->getName()
        );
        $category = $this->service->updateCategory($dto);
        return response()->json($category);
    }

    public function destroy(int $id)
    {
        return $this->service->deleteCategory($id);
    }
}
