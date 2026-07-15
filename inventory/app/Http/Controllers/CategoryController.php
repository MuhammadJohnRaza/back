<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(Request $request): View
    {
        $query = Category::withCount('products');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $categories = $query->paginate(15)->withQueryString();

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): View
    {
        Gate::authorize('create', Category::class);

        return view('categories.create');
    }

    /**
     * Store a newly created category.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        Gate::authorize('create', Category::class);

        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified category and its products.
     */
    public function show(Category $category): View
    {
        $category->load(['products' => function ($q) {
            $q->paginate(10);
        }]);

        $products = $category->products()->paginate(10);

        return view('categories.show', compact('category', 'products'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category): View
    {
        Gate::authorize('update', $category);

        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified category.
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        Gate::authorize('update', $category);

        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Category $category): RedirectResponse
    {
        Gate::authorize('delete', $category);

        if ($category->products()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Cannot delete category. It contains active products.');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
