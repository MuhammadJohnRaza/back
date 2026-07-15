<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the main metrics dashboard.
     */
    public function index(): View
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalStock = Product::sum('quantity');
        
        $lowStockCount = Product::whereColumn('quantity', '<=', 'minimum_stock_level')->count();
        $lowStockProducts = Product::with('category')
            ->whereColumn('quantity', '<=', 'minimum_stock_level')
            ->take(5)
            ->get();

        $recentMovements = StockMovement::with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalStock',
            'lowStockCount',
            'lowStockProducts',
            'recentMovements'
        ));
    }
}
