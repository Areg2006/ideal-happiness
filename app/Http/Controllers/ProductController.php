<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ProductService $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {

        $request->validate(['filters.q' => 'nullable',
            'filters.category_id' => 'required',
            'price_from' => 'nullable',
            'price_to' => 'nullable',
            'sort_by' => 'nullable',
            'sort' => 'nullable',
            'page' => 'nullable',
            'perPage' => 'nullable',]);
        $filters = $request->query('filters');

        return $this->service->index($filters);
    }

    public function store(ProductRequest $request)
    {
        return $this->service->createProduct($request->validated());
    }

    public function update(ProductRequest $request, int $id)
    {
        return $this->service->updateProduct($request->validated(), $id);
    }

    public function destroy(int $id)
    {
        return $this->service->deleteProduct($id);
    }

    public function show(int $id)
    {
        return $this->service->showProduct($id);
    }

    public function archive(int $id)
    {
        return $this->service->archiveProduct($id);
    }

    public function unarchive(int $id)
    {
        return $this->service->unarchiveProduct($id);
    }

    public function archived()
    {
        return $this->service->getArchivedProducts();
    }

    public function buy(int $productId)
    {
        return $this->service->buyProduct($productId);
    }
}
