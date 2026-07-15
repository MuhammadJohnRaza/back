<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Categories') }}
            </h2>
            @if(Auth::user()->isAdmin())
                <a href="{{ route('categories.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                    + Add Category
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

            @if(session('error'))
                <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 p-4 rounded shadow-sm text-sm text-red-800 dark:text-red-300">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Filters & Search -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 dark:border-gray-700">
                <form action="{{ route('categories.index') }}" method="GET" class="flex gap-4">
                    <div class="flex-1">
                        <x-text-input name="search" placeholder="Search by name or description..." value="{{ request('search') }}" class="w-full" />
                    </div>
                    <x-primary-button type="submit">
                        Search
                    </x-primary-button>
                    @if(request('search'))
                        <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <!-- Categories Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                @if($categories->isEmpty())
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        No categories found.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Products Count</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @foreach($categories as $category)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('categories.show', $category->id) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                {{ $category->name }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                            {{ $category->description ?? 'No description provided.' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-900 dark:text-gray-200 font-semibold">
                                            {{ $category->products_count }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            <a href="{{ route('categories.show', $category->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">View</a>
                                            @if(Auth::user()->isAdmin())
                                                <a href="{{ route('categories.edit', $category->id) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">Edit</a>
                                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this category?');">
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
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
