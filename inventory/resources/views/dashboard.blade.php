<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Quick Action Info / Role Badge -->
            <div class="bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 p-4 rounded shadow-sm">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-blue-700 dark:text-blue-300 font-medium">
                            Welcome back, <span class="font-bold">{{ Auth::user()->name }}</span>!
                        </p>
                        <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                            You are logged in as <span class="underline font-semibold">{{ ucfirst(Auth::user()->role) }}</span>.
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('stock-movements.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                            Log Stock Movement
                        </a>
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('products.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 dark:bg-emerald-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                                Add Product
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Total Products -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Products</div>
                                <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $totalProducts }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Categories -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900/50 text-purple-600 dark:text-purple-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Categories</div>
                                <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $totalCategories }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Stock Quantity -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Stock items</div>
                                <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($totalStock) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Low Stock Alert Count -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Low Stock items</div>
                                <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $lowStockCount }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Low Stock Products List -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            Low Stock Alerts
                        </h3>
                        @if($lowStockProducts->isEmpty())
                            <div class="text-sm text-gray-500 dark:text-gray-400 py-4 text-center">
                                All items are well-stocked!
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Product</th>
                                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">SKU</th>
                                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Stock</th>
                                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Min Level</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                        @foreach($lowStockProducts as $product)
                                            <tr>
                                                <td class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-gray-200">
                                                    <a href="{{ route('products.show', $product->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                        {{ $product->name }}
                                                    </a>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 font-mono">{{ $product->sku }}</td>
                                                <td class="px-4 py-3 text-sm font-bold text-red-600 dark:text-red-400">{{ $product->quantity }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $product->minimum_stock_level }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4 text-right">
                                <a href="{{ route('products.index', ['low_stock' => 'true']) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    View all low-stock products →
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Stock Movements -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Recent Stock Movements
                        </h3>
                        @if($recentMovements->isEmpty())
                            <div class="text-sm text-gray-500 dark:text-gray-400 py-4 text-center">
                                No stock movements recorded yet.
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Product</th>
                                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                        @foreach($recentMovements as $movement)
                                            <tr>
                                                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $movement->created_at->format('M d, H:i') }}
                                                </td>
                                                <td class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-gray-200">
                                                    <a href="{{ route('products.show', $movement->product_id) }}" class="hover:underline">
                                                        {{ $movement->product->name }}
                                                    </a>
                                                </td>
                                                <td class="px-4 py-3 text-sm">
                                                    @if($movement->type === 'in')
                                                        <span class="px-2.5 py-0.5 inline-flex text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                                                            IN
                                                        </span>
                                                    @else
                                                        <span class="px-2.5 py-0.5 inline-flex text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                            OUT
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-sm font-bold text-gray-900 dark:text-gray-200">
                                                    {{ $movement->quantity }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4 text-right">
                                <a href="{{ route('stock-movements.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    View all history →
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
