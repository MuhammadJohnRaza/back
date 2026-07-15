<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                <div class="p-6">
                    <form action="{{ route('products.update', $product->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Category Selection -->
                            <div>
                                <x-input-label for="category_id" :value="__('Category')" />
                                <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                            </div>

                            <!-- SKU -->
                            <div>
                                <x-input-label for="sku" :value="__('SKU (Stock Keeping Unit)')" />
                                <x-text-input id="sku" name="sku" type="text" class="mt-1 block w-full" :value="old('sku', $product->sku)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('sku')" />
                            </div>
                        </div>

                        <!-- Product Name -->
                        <div>
                            <x-input-label for="name" :value="__('Product Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $product->name)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description', $product->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Price -->
                            <div>
                                <x-input-label for="price" :value="__('Unit Price ($)')" />
                                <x-text-input id="price" name="price" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('price', $product->price)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('price')" />
                            </div>

                            <!-- Current Quantity (Read-only) -->
                            <div>
                                <x-input-label :value="__('Current Stock (Read Only)')" />
                                <div class="mt-1 flex items-center h-10 px-3 bg-gray-100 dark:bg-gray-900 text-gray-500 rounded-md shadow-sm border border-gray-300 dark:border-gray-750 font-bold justify-between">
                                    <span>{{ $product->quantity }}</span>
                                    <a href="{{ route('stock-movements.create', ['product_id' => $product->id]) }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">
                                        Log Adjustment
                                    </a>
                                </div>
                                <p class="text-xxs text-gray-500 mt-1">Stock quantity is managed via Stock Movements only.</p>
                            </div>

                            <!-- Minimum Stock Level -->
                            <div>
                                <x-input-label for="minimum_stock_level" :value="__('Minimum Stock Level')" />
                                <x-text-input id="minimum_stock_level" name="minimum_stock_level" type="number" min="0" class="mt-1 block w-full" :value="old('minimum_stock_level', $product->minimum_stock_level)" />
                                <x-input-error class="mt-2" :messages="$errors->get('minimum_stock_level')" />
                            </div>
                        </div>

                        <div class="flex items-center gap-4 border-t border-gray-100 dark:border-gray-700 pt-4">
                            <x-primary-button>{{ __('Update Product') }}</x-primary-button>
                            <a href="{{ route('products.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 underline">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
