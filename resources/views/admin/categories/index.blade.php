<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">Categories</h2>
            <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition duration-200 text-center">Add Category</a>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-white">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name (EN)</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden sm:table-cell">Name (BN)</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden md:table-cell">Slug</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($categories as $category)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-3 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $category->name_en }}</div>
                                            @if($category->name_bn)
                                                <div class="text-xs text-gray-500 dark:text-gray-400 sm:hidden">{{ $category->name_bn }}</div>
                                            @endif
                                            <div class="text-xs text-gray-500 dark:text-gray-400 md:hidden">{{ $category->slug }}</div>
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white hidden sm:table-cell">
                                            {{ $category->name_bn }}
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white hidden md:table-cell">
                                            {{ $category->slug }}
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex flex-col sm:flex-row gap-2">
                                                <a href="{{ route('admin.categories.edit',$category) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 text-xs sm:text-sm">
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.categories.destroy',$category) }}" method="POST" class="inline">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 text-xs sm:text-sm" 
                                                            onclick="return confirm('Delete?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $categories->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
