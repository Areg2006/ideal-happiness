<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 'partnership') {
            return response()->json(['message' => 'У вас нет доступа'], 403);
        }
        $searchTerm = $request->query('q');
        $products = Product::query()
            ->when($user->role === 'user', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->when($searchTerm, function ($query) use ($searchTerm) {
                $query->where('name', 'like', "%{$searchTerm}%");
            })
            ->when($request->filled('category_id'), function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })
            ->when($request->filled('price_from'), function ($query) use ($request) {
                $query->where('price', '>=', $request->price_from);
            })
            ->when($request->filled('price_to'), function ($query) use ($request) {
                $query->where('price', '<=', $request->price_to);
            })
            ->when($request->filled('sort_by'), function ($query) use ($request) {
                $price = $request->get('sort', 'asc');
                $query->orderBy($request->sort_by, $price);
                $query->orderBy('created_at');
            })
            ->where('is_archived', false)
            ->paginate($request->get('per_page'));

        return ProductResource::collection($products);
    }

    public function attach(int $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => "Продукт с идентификатором $id не найден"], 404);
        }

        $product->is_archived = true;
        $product->save();
        return response()->json(['message' => "Продукт с идентификатором $id архиворован"]);
    }

    public function showArchiveProduct()
    {
        $products = Product::query()->where('is_archived', true)->with('category')->get();

        return ProductResource::collection($products);
    }

    public function detach(int $id)
    {
        Product::where(['id' => $id])
            ->update(['is_archived' => false]);

        return response()->json(['message' => 'Продукт не архивирован']);
    }

    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (is_null($user)) {
            return response()->json([
                'error' => 'You are not authorized to access this resource'
            ], 401);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'category_id' => 'required|integer|exists:categories,id',
            'category_id.exists' => 'Категория не найдена',
        ]);
          $validated['user_id'] = $user->id;
        $product = Product::create($validated);

        return response()->json($product, 201);
    }

    public function show(int $id): JsonResponse
    {
        $product = Product::with('category')->find($id);
        if (!$product) {
            return response()->json(['message' => 'Продукт не найден'], 404);
        }

        return response()->json($product);
    }

    public function update(Request $request, int $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Продукт не найден'], 404);
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'category_id' => 'required|integer|exists:categories,id',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $product->update($request->all());

        return response()->json($product);
    }

    public function destroy(int $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Продукт не найден'], 404);
        }
        $product->delete();

        return response()->json(['message' => 'Продукт удалён']);
    }

}
