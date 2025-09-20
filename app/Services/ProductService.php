<?php

namespace App\Services;

use App\DTO\ProductStoreDTO;
use App\DTO\ProductUpdateDTO;
use App\Models\Product;
use App\ProductException\InvalidProductException;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductService
{
    protected ProductRepository $productRepo;
    protected UserRepository $userRepo;

    public function __construct(ProductRepository $productRepo, UserRepository $userRepo)
    {
        $this->productRepo = $productRepo;
        $this->userRepo = $userRepo;
    }

    public function index(array $filters = []): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Вы не авторизованы'], 401);
        }
        if ($user->role === 'admin') {
            $products = $this->productRepo->getAll($filters);
        } elseif ($user->role === 'user') {
            $products = $this->productRepo->getByUserId($user->id);
        } elseif ($user->role === 'partnership') {
            return response()->json(['message' => 'У вас нет доступа'], 403);
        } else {
            return response()->json(['message' => 'Роль не определена'], 400);
        }

        return response()->json($products, 200);
    }

    public function createProduct(ProductStoreDTO $dto): Product
    {
        $authUser = Auth::user();
        if (!$authUser) {
            abort(401, 'Вы не авторизованы');
        }

        return Product::create([
            'name' => $dto->name,
            'price' => $dto->price,
            'description' => $dto->description,
            'category_id' => $dto->categoryId,
        ]);

    }

    public function updateProduct(int $id, ProductUpdateDTO $dto): Product
    {
        $product = $this->productRepo->find($id);
        if (!$product) {
            abort(404, 'Продукт не найден');
        }

        $product->update([
            'id' => $dto->id,
            'name' => $dto->name,
            'price' => $dto->price,
            'description' => $dto->description,
            'category_id' => $dto->categoryId,
        ]);

        return $product;
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

    public function buyProduct(int $productId)
    {
        $user = auth('api')->user();

        if (!$user) {
            return ['status' => 'error', 'message' => 'Вы не авторизованы'];
        }

        $product = $this->productRepo->find($productId);
        if (!$product) {
            return ['status' => 'error', 'message' => "Продукт с ID {$productId} не найден"];
        }

        if ($product->count <= 0) {
            throw new InvalidProductException(
                "Товара '{$product->name}' нет в наличии");
        }
        echo "Вы купили товара '{$product->name}'";

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
