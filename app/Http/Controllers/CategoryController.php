<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Cache;
use App\Jobs\ProcessCategoryCreation;
use App\Jobs\ProcessCategoryUpdate;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Cache::remember('categories', 3600, function () {
                return CategoryResource::collection(Category::with('products')->get());
            }),
            'from_cache' => Cache::has('categories')
        ]);
    }

    public function store(CategoryRequest $request)
    {
        ProcessCategoryCreation::dispatch($request->validated())
            ->onQueue('category-operations');

        return response()->json([
            'success' => true,
            'message' => 'Category creation has been queued for processing'
        ], 202);
    }

    public function show(Category $category)
    {
        $cacheKey = 'category_' . $category->id;

        return response()->json([
            'success' => true,
            'data' => Cache::remember($cacheKey, 3600, function () use ($category) {
                $category->load('products');
                return new CategoryResource($category);
            }),
            'from_cache' => Cache::has($cacheKey)
        ]);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        ProcessCategoryUpdate::dispatch($category, $request->validated())
            ->onQueue('category-operations');

        return response()->json([
            'success' => true,
            'message' => 'Category update has been queued for processing'
        ], 202);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        Cache::forget('categories');
        Cache::forget('category_' . $category->id);

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ], 204);
    }

    public function getProducts(Category $category)
    {
        return response()->json([
            'success' => true,
            'data' => Cache::remember('category_'.$category->id.'_products', 3600, function () use ($category) {
                return ProductResource::collection($category->products);
            })
        ]);
    }
}
