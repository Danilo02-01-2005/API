<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use App\Http\Requests\ProductRequest;
use App\Jobs\ProcessProductUpdate;
use App\Jobs\ProcessProductCreation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\HtmlString;

class ProductController extends Controller
{
    protected function sanitizeInput($input)
    {
        if (is_string($input)) {
            return strip_tags($input);
        }
        return $input;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return ProductResource::collection($products);
    }

    public function store(ProductRequest $request)
    {
        $validated = collect($request->validated())
            ->map(fn($value) => $this->sanitizeInput($value))
            ->all();

        ProcessProductCreation::dispatch($validated)
            ->onQueue('product-creations');

        return response()->json([
            'success' => true,
            'message' => 'Product creation has been queued for processing'
        ], 202);
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $validated = collect($request->validated())
            ->map(fn($value) => $this->sanitizeInput($value))
            ->all();

        ProcessProductUpdate::dispatch($product, $validated)
            ->onQueue('product-updates');

        return response()->json([
            'success' => true,
            'message' => 'Product update has been queued for processing',
            'data' => new ProductResource($product)
        ]);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        Cache::forget('products');
        Cache::forget('product_'.$product->id);

        return response()->json(null, 204);
    }
}
