<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\ProductService;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;

class ProductController extends Controller
{
    public function index(Request $request)
    {

        $categoryId = $request->query('category_id', 0);

        $search = $request->query('search', '');

        $perPage = $request->query('per_page', 10);

        $products = ProductService::getProductsWithCategory($categoryId, $search, $perPage);

        return new ProductCollection($products);
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        return new ProductResource($product);
    }
}
