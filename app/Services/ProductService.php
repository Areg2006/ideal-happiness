<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProductService
{
    protected ProductRepository $productRepo;

    public function __construct(ProductRepository $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    public function listProducts()
    {
        $user = Auth::user();

        $validated = request()->validate([
            'filters' => 'nullable|array',
            'filters.category_id' => 'nullable|integer',
            'filters.q' => 'nullable|string|max:255',
            'filters.price_from' => 'nullable|integer|min:0',
            'filters.price_to' => 'nullable|integer|min:0',
            'filters.sort_by' => 'nullable|string',
            'filters.sort' => 'nullable|string|in:asc,desc',
            'filters.page' => 'nullable|integer|min:1',
            'filters.per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $filters = $validated['filters'] ?? [];

        if (!$user) {
            return response()->json(['error' => 'Вы не авторизованы'], 401);
        }

        if ($user->role === 'admin') {
            $products = $this->productRepo->getAll($filters);
            return response()->json($products, 200);
        }

        if ($user->role === 'user') {
            $products = $this->productRepo->getByUserId($user->id);
            return response()->json($products, 200);
        }

        if ($user->role === 'partnership') {
            return response()->json(['message' => 'У вас нет доступа'], 403);
        }

        return response()->json(['message' => 'Роль не определена'], 400);
    }

    public function createProduct(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Вы не авторизованы'], 401);
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric',
            'description' => 'nullable|string',
            'category_id' => 'required|integer|exists:categories,id',
        ]);

        $validated['user_id'] = $user->id;
        return $this->productRepo->create($validated);
    }

    public function updateProduct(Request $request, int $id)
    {
        $product = $this->productRepo->find($id);
        if (!$product) {
            return response()->json(['message' => 'Продукт не найден'], 404);
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric',
            'description' => 'nullable|string',
            'category_id' => 'required|integer|exists:categories,id',
            'user_id'     => 'required|integer|exists:users,id',
        ]);

        return $this->productRepo->update($product, $request->all());
    }

    public function deleteProduct(int $id)
    {
        $product = $this->productRepo->find($id);
        if (!$product) {
            return response()->json(['message' => 'Продукт не найден'], 404);
        }

        $this->productRepo->delete($product);
        return response()->json(['message' => 'Продукт удалён']);
    }

    public function showProduct(int $id)
    {
        $product = $this->productRepo->find($id);
        if (!$product) {
            return response()->json(['message' => 'Продукт не найден'], 404);
        }
        return $product->load('category');
    }

    public function archiveProduct(int $id)
    {
        $product = $this->productRepo->find($id);
        if (!$product) {
            return response()->json(['message' => "Продукт с ID $id не найден"], 404);
        }

        $this->productRepo->archive($product);
        return response()->json(['message' => "Продукт архивирован"]);
    }

    public function unarchiveProduct(int $id)
    {
        $this->productRepo->unarchive($id);
        return response()->json(['message' => 'Продукт разархивирован']);
    }

    public function getArchivedProducts()
    {
        return $this->productRepo->getArchived();
    }

    public function buyProduct($user, int $productId)
    {
        $product = $this->productRepo->find($productId);

        if (!$product) {
            return ['status' => 'error', 'message' => "Продукт с таким идентификатором не найден"];
        }
        if ($product->count <= 0) {
            return ['status' => 'error', 'message' => "Товар закончился"];
        }
        if ($user->balance < $product->price) {
            return ['status' => 'error', 'message' => "Недостаточно средств"];
        }

        DB::transaction(function () use ($user, $product) {
            $product->count -= 1;
            if ($product->count === 0) {
                $product->is_archived = 1;
            }

            $user->balance -= $product->price;

            $this->productRepo->save($product);
            $this->userRepo->save($user);
        });

        return ['status' => 'success', 'product' => $product, 'message' => 'Покупка успешно'];
    }
}
