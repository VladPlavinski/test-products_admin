<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        $products = Product::with('category')->paginate($perPage);
        return new ProductCollection($products);
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        return new ProductResource($product);
    }
}
