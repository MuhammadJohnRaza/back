<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Record Stock Movement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                <div class="p-6">
                    <form action="{{ route('stock-movements.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Product Selection -->
                        <div>
                            <x-input-label for="product_id" :value="__('Product')" />
                            <select id="product_id" name="product_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required autofocus>
                                <option value="" disabled selected>Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-qty="{{ $product->quantity }}" {{ old('product_id', $selectedProductId) == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} (SKU: {{ $product->sku }} | Current Stock: {{ $product->quantity }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('product_id')" />
                        </div>

                        <!-- Movement Type -->
                        <div>
                            <x-input-label :value="__('Adjustment Type')" />
                            <div class="mt-2 flex gap-6">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="type" value="in" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-indigo-650 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600" {{ old('type', 'in') === 'in' ? 'checked' : '' }} required>
                                    <span class="ms-2 text-sm text-gray-700 dark:text-gray-300 font-semibold">IN (Add Stock / Restock)</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="type" value="out" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-indigo-650 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600" {{ old('type') === 'out' ? 'checked' : '' }} required>
                                    <span class="ms-2 text-sm text-gray-700 dark:text-gray-300 font-semibold">OUT (Remove Stock / Dispatch)</span>
                                </label>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('type')" />
                        </div>

                        <!-- Quantity -->
                        <div>
                            <x-input-label for="quantity" :value="__('Quantity')" />
                            <x-text-input id="quantity" name="quantity" type="number" min="1" class="mt-1 block w-full" :value="old('quantity')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('quantity')" />
                        </div>

                        <!-- Reason -->
                        <div>
                            <x-input-label for="reason" :value="__('Reason / Reference')" />
                            <x-text-input id="reason" name="reason" type="text" class="mt-1 block w-full" :value="old('reason')" placeholder="e.g. Restock from Supplier / Customer Sale Order #1002" required />
                            <x-input-error class="mt-2" :messages="$errors->get('reason')" />
                        </div>

                        <div class="flex items-center gap-4 border-t border-gray-100 dark:border-gray-700 pt-4">
                            <x-primary-button>{{ __('Record Movement') }}</x-primary-button>
                            <a href="{{ route('stock-movements.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 underline">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
