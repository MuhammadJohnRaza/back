<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Stock Movements') }}
            </h2>
            <a href="{{ route('stock-movements.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                + Log Stock Movement
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Notifications -->
            @if(session('success'))
                <div class="bg-emerald-50 dark:bg-emerald-900/30 border-l-4 border-emerald-500 p-4 rounded shadow-sm text-sm text-emerald-800 dark:text-emerald-300">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 dark:border-gray-700">
                <form action="{{ route('stock-movements.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <!-- Product Selection -->
                    <div>
                        <x-input-label for="product_id" :value="__('Product')" />
                        <select id="product_id" name="product_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="">All Products</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} ({{ $product->sku }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Movement Type -->
                    <div>
                        <x-input-label for="type" :value="__('Type')" />
                        <select id="type" name="type" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="">All Types</option>
                            <option value="in" {{ request('type') === 'in' ? 'selected' : '' }}>IN (Addition)</option>
                            <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>OUT (Reduction)</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-2">
                        <x-primary-button type="submit" class="flex-1 justify-center">
                            Filter
                        </x-primary-button>
                        @if(request('product_id') || request('type'))
                            <a href="{{ route('stock-movements.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Movements Log Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                @if($movements->isEmpty())
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        No stock movements logged.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date & Time</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Product Name</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">SKU</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Qty</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Recorded By</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Reason</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @foreach($movements as $movement)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $movement->created_at->format('Y-m-d H:i:s') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-200">
                                            <a href="{{ route('products.show', $movement->product_id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                {{ $movement->product->name }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-mono">
                                            {{ $movement->product->sku }}
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
                        {{ $movements->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
