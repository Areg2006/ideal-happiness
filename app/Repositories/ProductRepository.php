<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class ProductRepository
{
    public function find(int $id)
    {
        return Product::find($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update($product, array $data)
    {
        $product->update($data);
        return $product;
    }

    public function delete($product)
    {
        $product->delete();
    }

    public function save($product)
    {
        $product->save();
    }

    public function archive($product)
    {
        $product->is_archived = true;
        $product->save();
    }

    public function unarchive($id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->is_archived = false;
            $product->save();
        }
    }

    public function getArchived()
    {
        return Product::where('is_archived', true)->with('category')->get();
    }
    public function getByUserId(int $userId)
    {
        return Product::with('user')->where('user_id', $userId)->get();
    }

    public function getAll(array $filters)
    {
        $query = Product::with('category')->where('is_archived', false);


        if (!empty($filters['q'])) {
            $query->where('name', 'like', "%{$filters['q']}%");
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['price_from'])) {
            $query->where('price', '>=', $filters['price_from']);
        }

        if (!empty($filters['price_to'])) {
            $query->where('price', '<=', $filters['price_to']);
        }

        if (!empty($filters['sort_by'])) {
            $sort = $filters['sort'] ?? 'asc';
            $query->orderBy($filters['sort_by'], $sort)->orderBy('created_at');
        }

        $perPage = !empty($filters['per_page']) ? $filters['per_page'] : 5;

        return $query->paginate($perPage);
    }
}
