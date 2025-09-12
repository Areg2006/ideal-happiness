<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected ProductService $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->listProducts();
    }

    public function store(Request $request)
    {
        return $this->service->createProduct($request);
    }

    public function update(Request $request, int $id)
    {
        return $this->service->updateProduct($request, $id);
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

    public function buy(int $id)
    {
        $user = Auth::user();
        return response()->json($this->service->buyProduct($user, $id));
    }
}
