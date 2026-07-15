<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource with filters.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::with('category');

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by search (name or SKU)
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filter by low stock
        if ($request->boolean('low_stock')) {
            $query->whereColumn('quantity', '<=', 'minimum_stock_level');
        }

        $products = $query->paginate($request->integer('per_page', 15));

        return response()->json([
            'data' => ProductResource::collection($products->items()),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        Gate::authorize('create', Product::class);

        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);

        $product = Product::create($validated);

        return response()->json([
            'message' => 'Product created successfully.',
            'data' => new ProductResource($product),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): JsonResponse
    {
        $product->load(['category', 'stockMovements.user']);
        return response()->json([
            'data' => new ProductResource($product),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        Gate::authorize('update', $product);

        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);

        $product->update($validated);

        return response()->json([
            'message' => 'Product updated successfully.',
            'data' => new ProductResource($product),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): JsonResponse
    {
        Gate::authorize('delete', $product);

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully.',
        ], 200);
    }
}
