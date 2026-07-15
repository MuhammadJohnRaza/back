<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockMovementRequest;
use App\Models\Product;
use App\Models\StockMovement;
use App\Services\Contracts\StockServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StockMovementController extends Controller
{
    protected StockServiceInterface $stockService;

    /**
     * Inject StockServiceInterface — resolved by the service container.
     */
    public function __construct(StockServiceInterface $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Display a listing of stock movements.
     */
    public function index(Request $request): View
    {
        $query = StockMovement::with(['product', 'user']);

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $movements = $query->latest()->paginate(15)->withQueryString();
        $products = Product::all();

        return view('stock_movements.index', compact('movements', 'products'));
    }

    /**
     * Show the form for creating a new stock movement.
     */
    public function create(Request $request): View
    {
        $products = Product::all();
        $selectedProductId = $request->input('product_id');

        return view('stock_movements.create', compact('products', 'selectedProductId'));
    }

    /**
     * Store a newly created stock movement.
     */
    public function store(StoreStockMovementRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $this->stockService->recordMovement($validated, $request->user()->id);
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        return redirect()->route('stock-movements.index')
            ->with('success', 'Stock movement recorded successfully.');
    }
}
