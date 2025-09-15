<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
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

    public function store(CategoryRequest $request)
    {
        return $this->service->createCategory($request->validated());
    }

    public function show(int $id)
    {
        return $this->service->showCategory($id);
    }

    public function update(CategoryRequest $request, int $id)
    {
        return $this->service->updateCategory($request->validated(), $id);
    }

    public function destroy(int $id)
    {
        return $this->service->deleteCategory($id);
    }
}
