<?php

namespace App\Http\Controllers;

use App\DTO\ProductStoreDTO;
use App\DTO\ProductUpdateDTO;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
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

    public function store(ProductStoreRequest $request)
    {
        $dto = new ProductStoreDTO(
            name:$request->getName(),
            price:$request->getPrice(),
            description:$request->getDescription(),
            categoryId: $request->getCategory_id()
        );

        $product= $this->service->createProduct($dto);

        return response()->json($product);
    }

    public function update(ProductUpdateRequest $request)
    {
        $dto = new ProductUpdateDTO(
            id:$request->getID(),
            name:$request->getName(),
            price:$request->getPrice(),
            description:$request->getDescription(),
            categoryId: $request->getCategory_id()
        );

        $product= $this->service->updateProduct($dto);
        return response()->json($product);
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
