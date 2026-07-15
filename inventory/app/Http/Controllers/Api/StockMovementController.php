<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStockMovementRequest;
use App\Http\Resources\StockMovementResource;
use App\Models\StockMovement;
use App\Services\Contracts\StockServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StockMovementController extends Controller
{
    protected StockServiceInterface $stockService;

    /**
     * Inject StockServiceInterface — resolved via the service container binding
     * in AppServiceProvider. This decouples the controller from the concrete
     * StockService class (Dependency Inversion Principle).
     */
    public function __construct(StockServiceInterface $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Display a listing of stock movements.
     */
    public function index(Request $request): JsonResponse
    {
        $query = StockMovement::with(['product', 'user']);

        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $movements = $query->latest()->paginate($request->integer('per_page', 15));

        return response()->json([
            'data' => StockMovementResource::collection($movements->items()),
            'meta' => [
                'current_page' => $movements->currentPage(),
                'last_page' => $movements->lastPage(),
                'per_page' => $movements->perPage(),
                'total' => $movements->total(),
            ]
        ]);
    }

    /**
     * Store a newly created stock movement.
     */
    public function store(StoreStockMovementRequest $request): JsonResponse
    {
        Gate::authorize('create', StockMovement::class);

        $validated = $request->validated();

        // Delegate to StockService which runs inside a DB transaction with
        // lockForUpdate() to prevent race conditions on concurrent stock updates.
        $movement = $this->stockService->recordMovement($validated, $request->user()->id);

        return response()->json([
            'message' => 'Stock movement recorded successfully.',
            'data' => new StockMovementResource($movement->load(['product', 'user'])),
        ], 201);
    }

    /**
     * Display the specified stock movement.
     */
    public function show(StockMovement $stockMovement): JsonResponse
    {
        $stockMovement->load(['product', 'user']);
        return response()->json([
            'data' => new StockMovementResource($stockMovement),
        ]);
    }
}
