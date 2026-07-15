<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Product Details: {{ $product->name }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-250 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                    Back to Products
                </a>
                <a href="{{ route('stock-movements.create', ['product_id' => $product->id]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                    Adjust Stock
                </a>
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('products.edit', $product->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                        Edit Info
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Low Stock Alert Banner -->
            @if($product->isLowStock())
                <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 p-4 rounded shadow-sm flex items-center gap-3">
                    <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <div>
                        <h4 class="font-bold text-red-800 dark:text-red-300">Safety Stock Level Warning!</h4>
                        <p class="text-sm text-red-700 dark:text-red-400 mt-0.5">
                            This product's current stock ({{ $product->quantity }}) is at or below its configured minimum stock level limit of {{ $product->minimum_stock_level }}.
                        </p>
                    </div>
                </div>
            @endif

            <!-- Product Specs -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700 p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Info Left -->
                <div class="space-y-4 md:col-span-2">
                    <div>
                        <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Product Name</span>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $product->name }}</h3>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Description</span>
                        <p class="text-gray-700 dark:text-gray-300 text-sm mt-1 whitespace-pre-line leading-relaxed">
                            {{ $product->description ?? 'No description provided.' }}
                        </p>
                    </div>
                </div>

                <!-- Info Right (Stats Card) -->
                <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-lg space-y-4 border border-gray-100 dark:border-gray-800">
                    <div class="flex justify-between items-center pb-2 border-b border-gray-200 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">SKU</span>
                        <span class="font-mono text-sm font-semibold text-gray-900 dark:text-gray-200">{{ $product->sku }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-2 border-b border-gray-200 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Category</span>
                        <a href="{{ route('categories.show', $product->category_id) }}" class="text-sm font-semibold text-indigo-600 hover:underline dark:text-indigo-400">
                            {{ $product->category->name }}
                        </a>
                    </div>
                    <div class="flex justify-between items-center pb-2 border-b border-gray-200 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Unit Price</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-gray-200">${{ number_format($product->price, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-2 border-b border-gray-200 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Current Stock</span>
                        <span class="text-lg font-extrabold {{ $product->isLowStock() ? 'text-red-600 dark:text-red-400' : 'text-emerald-600 dark:text-emerald-400' }}">{{ $product->quantity }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Minimum Level</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-gray-200">{{ $product->minimum_stock_level }}</span>
                    </div>
                </div>
            </div>

            <!-- Stock Movement History -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                        Stock Movement History (Audit Logs)
                    </h3>
                </div>
                
                @if($stockMovements->isEmpty())
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        No transactions logged for this product.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date & Time</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Recorded By</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Reason</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @foreach($stockMovements as $movement)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $movement->created_at->format('M d, Y - H:i:s') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200 font-bold">
                                            {{ $movement->quantity }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $movement->user->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $movement->reason }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-6 border-t border-gray-100 dark:border-gray-700">
                        {{ $stockMovements->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
