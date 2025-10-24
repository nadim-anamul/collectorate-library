<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">{{ __('ui.authors') }}</h2>
            <a href="{{ route('admin.authors.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 text-center">{{ __('ui.add_author') }}</a>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-4 sm:p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('ui.name_en') }}</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden sm:table-cell">{{ __('ui.name_bn') }}</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('ui.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($authors as $author)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <td class="px-3 py-3 text-gray-900 dark:text-white font-medium">{{ $author->name_en }}</td>
                                    <td class="px-3 py-3 text-gray-600 dark:text-gray-300 hidden sm:table-cell">{{ $author->name_bn }}</td>
                                    <td class="px-3 py-3">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.authors.edit', $author) }}" class="inline-flex items-center px-3 py-1.5 text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-md transition duration-150">{{ __('ui.edit') }}</a>
                                            <form method="POST" action="{{ route('admin.authors.destroy', $author) }}" class="inline">
                                                @csrf @method('DELETE')
                                                <button onclick="return confirm('{{ __('ui.delete_author_confirm') }}')" class="inline-flex items-center px-3 py-1.5 text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition duration-150">{{ __('ui.delete') }}</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $authors->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>


