<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of products with filters.
     */
    public function index(Request $request): View
    {
        $query = Product::with('category');

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by search (name or SKU)
        if ($request->filled('search')) {
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

        $products = $query->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        Gate::authorize('create', Product::class);
        $categories = Category::all();

        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created product.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        Gate::authorize('create', Product::class);

        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);

        // Default quantity to 0 if not provided
        if (!isset($validated['quantity'])) {
            $validated['quantity'] = 0;
        }

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified product and its stock history.
     */
    public function show(Product $product): View
    {
        $product->load(['category']);
        $stockMovements = $product->stockMovements()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('products.show', compact('product', 'stockMovements'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        Gate::authorize('update', $product);
        $categories = Category::all();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        Gate::authorize('update', $product);

        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product): RedirectResponse
    {
        Gate::authorize('delete', $product);

        // Delete associated stock movements first if needed or let DB constraints handle it
        $product->stockMovements()->delete();
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
