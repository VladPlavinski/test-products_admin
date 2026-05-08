<?php

namespace App\Services;

use App\Models\Product;
class ProductService
{
    public static function getProductsWithCategory(int $categoryId = 0, string $search = '', int $perPage = 10)
    {
        $query = Product::with(['category']);
        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }
        return $query->paginate($perPage);
    }
}
