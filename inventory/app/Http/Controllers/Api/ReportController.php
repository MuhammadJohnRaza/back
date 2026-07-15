<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Get a list of products that are currently low on stock.
     */
    public function lowStock(): JsonResponse
    {
        $lowStockProducts = Product::with('category')
            ->whereColumn('quantity', '<=', 'minimum_stock_level')
            ->get();

        return response()->json([
            'data' => ProductResource::collection($lowStockProducts),
        ]);
    }

    /**
     * Get an inventory summary reporting key stats.
     */
    public function summary(): JsonResponse
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        
        // Total stock value (price * quantity)
        $totalStockValue = Product::selectRaw('SUM(price * quantity) as total_value')->first()->total_value ?? 0;
        
        // Low stock count
        $lowStockCount = Product::whereColumn('quantity', '<=', 'minimum_stock_level')->count();
        
        // Movements today
        $movementsToday = StockMovement::whereDate('created_at', Carbon::today())->count();

        // Top 5 moving products
        $topMovingProducts = StockMovement::select('product_id', DB::raw('count(*) as movements_count'))
            ->groupBy('product_id')
            ->orderBy('movements_count', 'desc')
            ->limit(5)
            ->with('product')
            ->get()
            ->map(function ($movement) {
                return [
                    'product_id' => $movement->product_id,
                    'name' => $movement->product?->name,
                    'sku' => $movement->product?->sku,
                    'movements_count' => $movement->movements_count,
                ];
            });

        return response()->json([
            'data' => [
                'total_products' => $totalProducts,
                'total_categories' => $totalCategories,
                'total_stock_value' => (float) $totalStockValue,
                'low_stock_count' => $lowStockCount,
                'movements_today' => $movementsToday,
                'top_moving_products' => $topMovingProducts,
            ]
        ]);
    }
}
