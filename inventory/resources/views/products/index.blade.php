<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Products') }}
            </h2>
            @if(Auth::user()->isAdmin())
                <a href="{{ route('products.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                    + Add Product
                </a>
            @endif
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
                <form action="{{ route('products.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <!-- Search -->
                    <div>
                        <x-input-label for="search" :value="__('Search SKU / Name')" />
                        <x-text-input id="search" name="search" type="text" class="mt-1 block w-full" value="{{ request('search') }}" placeholder="Search..." />
                    </div>

                    <!-- Category -->
                    <div>
                        <x-input-label for="category_id" :value="__('Category')" />
                        <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Low Stock Checkbox -->
                    <div class="flex items-center h-10">
                        <label for="low_stock" class="inline-flex items-center cursor-pointer">
                            <input id="low_stock" type="checkbox" name="low_stock" value="1" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600" {{ request('low_stock') ? 'checked' : '' }}>
                            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400 font-medium">Show Low Stock Only</span>
                        </label>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-2">
                        <x-primary-button type="submit" class="flex-1 justify-center">
                            Filter
                        </x-primary-button>
                        @if(request('search') || request('category_id') || request('low_stock'))
                            <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Products Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                @if($products->isEmpty())
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        No products found.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">SKU</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Product Name</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Qty</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Min Level</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @foreach($products as $product)
                                    <tr class="{{ $product->isLowStock() ? 'bg-red-50/20 dark:bg-red-900/10' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-mono">
                                            {{ $product->sku }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-gray-200">
                                                <a href="{{ route('products.show', $product->id) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                                    {{ $product->name }}
                                                </a>
                                            </div>
                                            @if($product->isLowStock())
                                                <span class="px-2 py-0.5 inline-flex text-xxs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 mt-1">
                                                    Low Stock
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $product->category->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                            ${{ number_format($product->price, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                            <span class="font-bold {{ $product->isLowStock() ? 'text-red-600 dark:text-red-400' : '' }}">
                                                {{ $product->quantity }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $product->minimum_stock_level }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            <a href="{{ route('stock-movements.create', ['product_id' => $product->id]) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Adjust</a>
                                            <a href="{{ route('products.show', $product->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">View</a>
                                            @if(Auth::user()->isAdmin())
                                                <a href="{{ route('products.edit', $product->id) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">Edit</a>
                                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this product? All of its stock movement history will be deleted.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-6 border-t border-gray-100 dark:border-gray-700">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
